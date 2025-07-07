<?php

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Auth\VerifyController;
use Illuminate\Support\Facades\Route;

Route::post('/auth',AuthController::class);
Route::post('/verify',VerifyController::class);