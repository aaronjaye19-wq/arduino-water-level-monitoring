<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

// Add this line at the top — this is the "import"
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Redirect root to login
Route::get('/', function() {
    if (session()->has('authenticated')) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Authentication Routes (public)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])->name('verify-email');
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset-password.show');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware('auth.session')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
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
