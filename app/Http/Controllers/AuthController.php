<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\UserStorageService;
use App\Services\VerificationService;
use App\Services\PasswordResetService;

class AuthController extends Controller
{
    private $userService;
    private $verificationService;
    private $passwordResetService;

    public function __construct(
        UserStorageService $userService,
        VerificationService $verificationService,
        PasswordResetService $passwordResetService
    ) {
        $this->userService = $userService;
        $this->verificationService = $verificationService;
        $this->passwordResetService = $passwordResetService;
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->userService->findByEmail($credentials['email']);

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.'])->onlyInput('email');
        }

        if (!Hash::check($credentials['password'], $user['password'])) {
            return back()->withErrors(['password' => 'Password is incorrect.'])->onlyInput('email');
        }

        if (!$user['email_verified']) {
            session()->put('email_for_verification', $user['email']);
            return redirect()->route('verify-email')->with('message', 'Please verify your email first.');
        }

        // Login successful
        session()->put('user_id', $user['id']);
        session()->put('user_email', $user['email']);
        session()->put('user_name', $user['name']);
        session()->put('user_role', $user['role']);
        session()->put('authenticated', true);

        return redirect()->route('dashboard');
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Check if email already exists
        if ($this->userService->findByEmail($validated['email'])) {
            return back()->withErrors(['email' => 'Email already registered.'])->onlyInput('email', 'name');
        }

        // Create user
        $user = $this->userService->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'user',
        ]);

        // Generate verification code
        $code = $this->verificationService->generateCode($validated['email']);

        // Store email in session for verification
        session()->put('email_for_verification', $validated['email']);

        return redirect()->route('verify-email')
            ->with('message', "Verification code has been generated and logged. Check your console or the message below.");
    }

    // Show verify email form
    public function showVerifyEmail()
    {
        $email = session()->get('email_for_verification');
        
        if (!$email) {
            return redirect()->route('login')->with('message', 'Please register or login first.');
        }

        // Get current code for display purposes
        $code = $this->verificationService->getCodeForEmail($email);

        return view('auth.verify-email', ['email' => $email, 'code' => $code]);
    }

    // Handle email verification
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $email = session()->get('email_for_verification');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Invalid session.');
        }

        if ($this->verificationService->verifyCode($email, $request->code)) {
            $this->userService->verifyEmail($email);
            session()->forget('email_for_verification');
            session()->put('user_id', $this->userService->findByEmail($email)['id']);
            session()->put('user_email', $email);
            session()->put('user_name', $this->userService->findByEmail($email)['name']);
            session()->put('user_role', $this->userService->findByEmail($email)['role']);
            session()->put('authenticated', true);

            return redirect()->route('dashboard')->with('success', 'Email verified successfully!');
        }

        return back()->withErrors(['code' => 'Invalid or expired verification code.']);
    }

    // Show forgot password form
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Handle forgot password request
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = $this->userService->findByEmail($request->email);

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.'])->onlyInput('email');
        }

        // Generate reset token
        $token = $this->passwordResetService->generateToken($request->email);

        session()->put('reset_email', $request->email);

        return redirect()->route('reset-password.show')
            ->with('message', "Password reset token has been generated. Use it to reset your password.");
    }

    // Show reset password form
    public function showResetPassword(Request $request)
    {
        $token = $request->query('token');
        $resetEmail = session()->get('reset_email');

        if (!$token && !$resetEmail) {
            return redirect()->route('forgot-password')->with('error', 'Invalid reset link.');
        }

        if ($token) {
            $email = $this->passwordResetService->verifyToken($token);
            if (!$email) {
                return redirect()->route('forgot-password')->with('error', 'Reset token expired or invalid.');
            }
            session()->put('reset_email', $email);
            session()->put('reset_token', $token);
        }

        return view('auth.reset-password', [
            'email' => session()->get('reset_email'),
            'token' => session()->get('reset_token'),
        ]);
    }

    // Handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $email = session()->get('reset_email');

        if (!$email) {
            return redirect()->route('forgot-password')->with('error', 'Invalid session.');
        }

        $this->userService->updatePassword($email, $request->password);

        if (session()->has('reset_token')) {
            $this->passwordResetService->consumeToken(session()->get('reset_token'));
            session()->forget('reset_token');
        }

        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password reset successfully! Please login with your new password.');
    }

    // Logout
    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
