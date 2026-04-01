<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::prefix('auth')->name('auth.')->group(function () {
    // Registration
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.store');
    
    // Email verification
    Route::get('verify-email/{userId}', [AuthController::class, 'showEmailVerifyNotice'])
        ->name('verify-email-notice');
    Route::get('verify-email', [AuthController::class, 'verifyEmail'])
        ->name('verify-email');
    
    // Login
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
    
    // MFA verification
    Route::get('mfa-verify', [AuthController::class, 'showMfaVerify'])->name('mfa-verify');
    Route::post('mfa-verify', [AuthController::class, 'verifyMfa'])->name('mfa-verify.store');
    
    // Password reset
    Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])
        ->name('forgot-password');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('forgot-password.store');
    Route::get('reset-password', [AuthController::class, 'showResetPassword'])
        ->name('reset-password');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])
        ->name('reset-password.store');
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('dashboard.admin');
    Route::get('/dashboard/user', [DashboardController::class, 'userDashboard'])
        ->middleware('role:user')
        ->name('dashboard.user');
    
    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Arduino POST route WITHOUT CSRF
Route::post('/api/sensor', function(Request $request){
    $data = $request->only(['sensor','green','yellow','red']);
    cache()->put('latest_sensor', $data, 60); // store latest reading 60s
    return response()->json(['status'=>'ok']);
})->withoutMiddleware([VerifyCsrfToken::class]);

// Return latest sensor data
Route::get('/api/latest-sensor', function(){
    return response()->json(
        cache('latest_sensor', ['sensor'=>0,'green'=>0,'yellow'=>0,'red'=>0])
    );
});
