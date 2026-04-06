<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Models\PasswordResetTokenDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_verified' => false,
        ]);

        // Generate verification token
        $token = EmailVerificationToken::generateToken();
        EmailVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        // Send verification email
        $verificationUrl = route('verify-email', ['token' => $token]);
        Mail::to($user->email)->send(new \App\Mail\VerifyEmailMail($user, $verificationUrl));

        return redirect()->route('login')->with('success', 'Registration successful! Please verify your email to access your dashboard.');
    }

    public function verifyEmail($token)
    {
        $verification = EmailVerificationToken::where('token', $token)->first();

        if (!$verification) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        $user = $verification->user;
        $user->update(['is_verified' => true]);
        $verification->delete();

        return redirect()->route('login')->with('success', 'Email verified! You can now login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        if (!$user->is_verified) {
            return back()->withErrors(['email' => 'Please verify your email before logging in.']);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found in our records.');
        }

        // Delete existing tokens for this user
        PasswordResetTokenDetail::where('user_id', $user->id)->delete();

        // Create new reset token (expires in 10 minutes)
        $token = PasswordResetTokenDetail::generateToken();
        PasswordResetTokenDetail::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send reset email
        $resetUrl = route('reset-password', ['token' => $token]);
        Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($user, $resetUrl));

        return back()->with('success', 'Password reset link has been sent to your email.');
    }

    public function showResetPassword($token)
    {
        $resetToken = PasswordResetTokenDetail::where('token', $token)->first();

        if (!$resetToken || $resetToken->isExpired()) {
            return redirect()->route('forgot-password')->with('error', 'Password reset link has expired.');
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $resetToken = PasswordResetTokenDetail::where('token', $token)->first();

        if (!$resetToken || $resetToken->isExpired()) {
            return back()->with('error', 'Password reset link has expired.');
        }

        $user = $resetToken->user;
        $user->update(['password' => $request->password]);
        $resetToken->delete();

        return redirect()->route('login')->with('success', 'Password has been reset. Please login with your new password.');
    }
}
