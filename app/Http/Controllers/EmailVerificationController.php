<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Verify the email using the token from the email link.
     * Auto-logs in the user and redirects to dashboard.
     */
    public function verify(string $token): RedirectResponse|View
    {
        // Find the token
        $verificationToken = EmailVerificationToken::where('token', $token)->first();

        // Token not found
        if (!$verificationToken) {
            return view('verification-error', [
                'message' => 'Invalid verification link. Please request a new verification email.',
            ]);
        }

        // Token expired
        if ($verificationToken->isExpired()) {
            // Delete expired token
            $verificationToken->delete();
            
            return view('verification-error', [
                'message' => 'Your verification link has expired. Please request a new verification email.',
                'userId' => $verificationToken->user_id,
            ]);
        }

        // Token is valid - mark email as verified
        $user = $verificationToken->user;
        $user->update(['email_verified_at' => now()]);

        // Delete the token after use
        $verificationToken->delete();

        // Auto-login the user
        Auth::login($user);

        // Redirect to dashboard
        return redirect('/dashboard')->with('success', 'Email verified successfully! Welcome to your dashboard.');
    }
}
