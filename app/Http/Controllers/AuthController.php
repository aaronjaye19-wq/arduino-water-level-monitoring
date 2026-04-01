<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        // Generate email verification token
        $token = Str::random(60);
        EmailVerification::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        // Send verification email (you can implement email sending here)
        // Mail::send('emails.verify-email', ['token' => $token, 'user' => $user], function ($message) use ($user) {
        //     $message->to($user->email)->subject('Verify Your Email');
        // });

        return redirect()->route('auth.verify-email-notice', $user->id)
            ->with('success', 'Registration successful! Please verify your email.');
    }

    /**
     * Show email verification notice
     */
    public function showEmailVerifyNotice($userId): View
    {
        $user = User::findOrFail($userId);
        return view('auth.verify-email-notice', ['user' => $user]);
    }

    /**
     * Verify email with token
     */
    public function verifyEmail(Request $request): RedirectResponse
    {
        $token = $request->query('token');
        $userId = $request->query('user_id');

        $verification = EmailVerification::where('token', $token)
            ->where('user_id', $userId)
            ->first();

        if (!$verification) {
            return redirect()->route('auth.register')
                ->with('error', 'Invalid verification token.');
        }

        if ($verification->isExpired()) {
            $verification->delete();
            return redirect()->route('auth.register')
                ->with('error', 'Verification token has expired.');
        }

        $user = $verification->user;
        $user->update(['email_verified_at' => now()]);
        $verification->delete();

        return redirect()->route('auth.login')
            ->with('success', 'Email verified successfully! Please log in.');
    }

    /**
     * Show login form
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        // Check if email is verified
        if (!$user->isEmailVerified()) {
            return redirect()->route('auth.verify-email-notice', $user->id)
                ->with('error', 'Please verify your email before logging in.');
        }

        // Generate MFA code
        $mfaCode = $user->generateMfaCode();

        // Store in session to track login attempt
        session(['auth_pending_user_id' => $user->id]);

        // In a real app, send MFA code via email
        // For development, you can display it or log it
        \Log::info('MFA Code for user ' . $user->email . ': ' . $mfaCode);

        return redirect()->route('auth.mfa-verify')
            ->with('success', 'A 6-digit code has been sent to your email.');
    }

    /**
     * Show MFA verification form
     */
    public function showMfaVerify(): View
    {
        if (!session('auth_pending_user_id')) {
            return redirect()->route('auth.login');
        }
        return view('auth.mfa-verify');
    }

    /**
     * Verify MFA code
     */
    public function verifyMfa(Request $request): RedirectResponse
    {
        $userId = session('auth_pending_user_id');
        if (!$userId) {
            return redirect()->route('auth.login');
        }

        $request->validate([
            'mfa_code' => 'required|digits:6',
        ]);

        $user = User::findOrFail($userId);

        if (!$user->verifyMfaCode($request->mfa_code)) {
            return back()->withErrors(['mfa_code' => 'Invalid or expired MFA code.']);
        }

        // Clear session and authenticate user
        session()->forget('auth_pending_user_id');
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Login successful!');
    }

    /**
     * Show password reset request form
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset request
     */
    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email|exists:users']);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);

        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(1), // 60 seconds as per requirement
            ]
        );

        // In a real app, send reset link via email
        // For development, you can log it or return it
        $resetUrl = route('auth.reset-password', ['token' => $token, 'email' => $user->email]);
        \Log::info('Password reset link: ' . $resetUrl);

        return back()->with('success', 'If that email exists, a password reset link has been sent.');
    }

    /**
     * Show password reset form
     */
    public function showResetPassword(Request $request): View
    {
        $token = $request->query('token');
        $email = $request->query('email');

        $resetToken = \DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$resetToken || !$resetToken->expires_at || $resetToken->expires_at < now()) {
            return redirect()->route('auth.forgot-password')
                ->with('error', 'Password reset link has expired.');
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetToken = \DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->where('token', $validated['token'])
            ->first();

        if (!$resetToken || !$resetToken->expires_at || $resetToken->expires_at < now()) {
            return back()->with('error', 'Password reset link has expired.');
        }

        $user = User::where('email', $validated['email'])->first();
        $user->update(['password' => Hash::make($validated['password'])]);

        \DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return redirect()->route('auth.login')
            ->with('success', 'Password has been reset successfully!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')
            ->with('success', 'Logged out successfully!');
    }
}
