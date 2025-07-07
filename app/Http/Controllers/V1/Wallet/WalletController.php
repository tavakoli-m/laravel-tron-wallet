<?php

namespace App\Http\Controllers\V1\Wallet;

use App\Api\ApiResponse\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Wallet\WalletListApiResource;
use App\Models\Wallet;
use App\Services\TronService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        foreach($wallets as $wallet) {
            $wallet->balance = $tronService->getTrxBalance($wallet->address);
            $wallet->save();
        }

        return ApiResponse::send();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
