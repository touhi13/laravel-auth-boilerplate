<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */
Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('email-validate', [AuthController::class, 'emailValidate']);
    Route::post('resend-email-otp', [AuthController::class, 'resendEmailVerificationOtp']);
    Route::post('otp-verification', [AuthController::class, 'otpVerification']);
    Route::post('forgot-password-otp', [AuthController::class, 'forgotPasswordOtp']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('logout', [AuthController::class, 'logout']);

});
