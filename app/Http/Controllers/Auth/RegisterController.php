<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpCode;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'is_verified' => false,
        ]);

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        OtpVerification::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp_code' => $otp,
            'expires_at' => now()->addMinutes(15),
            'attempts' => 0,
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new SendOtpCode($user, $otp));

        return redirect()->route('verify.otp.form', ['email' => $user->email])
            ->with('success', 'Registration successful! Please verify your email.');
    }
}
