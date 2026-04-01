<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpCode;

class OtpVerificationController extends Controller
{
    public function showVerifyForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.verify-otp', ['email' => $email]);
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        $otpRecord = OtpVerification::where('email', $validated['email'])->latest()->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp_code' => 'OTP not found. Please register again.']);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors(['otp_code' => 'OTP has expired. Please request a new one.']);
        }

        if ($otpRecord->attempts >= 3) {
            return back()->withErrors(['otp_code' => 'Too many failed attempts. Please request a new OTP.']);
        }

        if (!$otpRecord->isValid($validated['otp_code'])) {
            $otpRecord->increment('attempts');
            return back()->withErrors(['otp_code' => 'Invalid OTP code. Please try again.']);
        }

        // Mark user as verified
        $user = User::where('email', $validated['email'])->first();
        $user->update(['is_verified' => true]);
        $otpRecord->delete();

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Email verified successfully!');
    }

    public function resendOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Delete old OTP records
        OtpVerification::where('email', $validated['email'])->delete();

        // Generate new OTP
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

        return back()->with('success', 'New OTP sent to your email!');
    }
}
