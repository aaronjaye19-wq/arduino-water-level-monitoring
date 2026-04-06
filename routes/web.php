<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Add this line at the top — this is the "import"
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EmailVerificationController;

// Registration routes
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Email verification route
Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verify'])->name('verify-email');

// Dashboard page (protected by EnsureEmailIsVerified middleware)
Route::middleware(['App\Http\Middleware\EnsureEmailIsVerified'])->group(function () {
    Route::view('/dashboard', 'dashboard');
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
