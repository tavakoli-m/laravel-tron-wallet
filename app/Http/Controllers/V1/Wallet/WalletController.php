<?php

namespace App\Http\Controllers\V1\Wallet;

use App\Api\ApiResponse\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Wallet\WithdrawalRequest;
use App\Http\Resources\V1\Wallet\WalletListApiResource;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\TronService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = Wallet::whereUserId(Auth::id())->select(['id', 'balance', 'address'])->get();

        return ApiResponse::withData([
            'wallets' => WalletListApiResource::collection($wallets)
        ])->send();
    }

    public function refresh(TronService $tronService)
    {
        $wallets = Wallet::whereUserId(Auth::id())->get();
        foreach ($wallets as $wallet) {
            $wallet->balance = $tronService->getTrxBalance($wallet->address);
            $wallet->save();
        }

        return ApiResponse::send();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TronService $tronService)
    {
        $wallet = $tronService->createWallet();

        $walletInstance = Wallet::create([
            'user_id' => Auth::id(),
            'address' => $wallet['public_address'],
            'key' => Crypt::encryptString($wallet['private_key']),
            'balance' => 0.0,
        ]);

        return ApiResponse::withData([
            'wallet' => new WalletListApiResource($walletInstance)
        ])->send();
    }


    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) {
            throw new AuthorizationException();
        }

        return  ApiResponse::withData([
            'wallet' => new WalletListApiResource($wallet)
        ])->send();
    }

    /**
     * Display the specified resource.
     */
    public function withdrawal(WithdrawalRequest $request, Wallet $wallet, TronService $tronService)
    {
        if ($wallet->user_id !== Auth::id()) {
            throw new AuthorizationException();
        }

        if ($request->amount > $wallet->balance) {
            return ApiResponse::withMessage('Insufficient balance')->withStatus(400)->send();
        }

        try {
            DB::beginTransaction();


            $blockChainTransaction = $tronService->sendTrx(
                Crypt::decryptString($wallet->key),
                $request->address,
                $request->amount,
                $wallet->address
            );


            $wallet->balance = $tronService->getTrxBalance($wallet->address);
            $wallet->save();

            DB::commit();

            return ApiResponse::send();
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::withMessage('Insufficient balance')->withStatus(400)->send();
        }
    }
}
