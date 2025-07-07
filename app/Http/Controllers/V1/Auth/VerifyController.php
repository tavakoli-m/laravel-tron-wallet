<?php

namespace App\Http\Controllers\V1\Auth;

use App\Api\ApiResponse\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\VerifyRequest;
use App\Models\Otp;
use App\Models\User;

class VerifyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(VerifyRequest $request)
    {
        $token = $request->input('token');
        $password = $request->input('password');

        if (!$otp = Otp::where('token', $token)
            ->where('expire_at', '>', now())->first()) {
            return ApiResponse::withMessage('Invalid or expired token.')
                ->withStatus(422)
                ->send();
        }

        if ((int)$otp->password !== (int)$password) {
            return ApiResponse::withMessage('Invalid password.')
                ->withStatus(422)
                ->send();
        }

        $user = User::find($otp->user_id);

        $otp->delete();

        $api_token = $user->createToken('api_token')->plainTextToken;

        return ApiResponse::withData([
            'api_token' => $api_token,
        ])->send();

    }
}
