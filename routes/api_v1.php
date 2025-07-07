<?php

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Auth\VerifyController;
use App\Http\Controllers\V1\Wallet\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('/auth',AuthController::class);
Route::post('/verify',VerifyController::class);


Route::middleware('auth:sanctum')->prefix('wallet')->controller(WalletController::class)->group(function(){
    Route::get('/','index');
    Route::post('/create','store');
    Route::get('/refresh','refresh');
    Route::get('/{wallet}','show');
});
