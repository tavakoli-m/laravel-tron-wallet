<?php

namespace App\Http\Controllers\V1\Auth;

use App\Api\ApiResponse\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\AuthRequest;
use App\Jobs\V1\Auth\SendOtpJob;
use App\Models\Otp;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AuthRequest $request)
    {
        $email = $request->validated('email');

        if (!$user = User::whereEmail($email)->first()) {
            $user = User::create($request->validated());
        }

        if (Otp::whereUserId($user->id)->where('expire_at', '>', now())->first()) {
            return ApiResponse::withMessage('otp already sent')->send();
        }

        $otp = Otp::create([
            'user_id' => $user->id,
            'password' => rand(100000, 999999),
            'expire_at' => now()->addMinutes(5),
            'token' => str()->random(64)
        ]);

        SendOtpJob::dispatch($otp,$email);

        return ApiResponse::withMessage('otp sent')->withData(['token' => $otp->token])->send();
    }
}
