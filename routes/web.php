<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Add this line at the top — this is the "import"
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('send-reset-link');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password');
Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('reset-password-submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// MFA Routes
Route::get('/mfa/verify', [AuthController::class, 'showMfaVerify'])->name('mfa.verify');
Route::post('/mfa/verify', [AuthController::class, 'verifyMfa'])->name('mfa.verify.submit');

Route::middleware('auth')->group(function () {
    Route::get('/mfa/setup', [AuthController::class, 'showSetupMfa'])->name('mfa.setup');
    Route::post('/mfa/setup', [AuthController::class, 'enableMfa'])->name('mfa.setup.submit');
    Route::post('/mfa/disable', [AuthController::class, 'disableMfa'])->name('mfa.disable');
});

// Dashboard page (Protected)
Route::view('/dashboard', 'dashboard')->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'userManagement'])->name('admin.users');
    Route::post('/admin/users/{id}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.toggle-admin');
    Route::post('/admin/users/{id}/disable-mfa', [AdminController::class, 'disableMfa'])->name('admin.disable-mfa');
    Route::post('/admin/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
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
