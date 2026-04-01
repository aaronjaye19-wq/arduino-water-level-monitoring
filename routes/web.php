<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// OTP Verification
Route::get('/verify-otp', function () {
    return view('auth.verify-otp');
})->name('verify.otp.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/resend-otp', [OtpVerificationController::class, 'resendOtp'])->name('resend.otp');

// Forgot Password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot.password.form');
Route::post('/send-reset-link', [ForgotPasswordController::class, 'sendResetLink'])->name('send.reset.link');

// Reset Password
Route::get('/password/reset/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset.form');
Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword'])->name('reset.password');

// Dashboard - Protected
Route::get('/dashboard', function() {
    return view('dashboard');
})->name('dashboard');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Arduino API Routes (WITHOUT CSRF - for sensor data)
Route::post('/api/sensor', function(Request $request){
    $data = $request->only(['sensor','green','yellow','red']);
    cache()->put('latest_sensor', $data, 60);
    return response()->json(['status'=>'ok']);
})->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/api/latest-sensor', function(){
    return response()->json(
        cache('latest_sensor', ['sensor'=>0,'green'=>0,'yellow'=>0,'red'=>0])
    );
});
