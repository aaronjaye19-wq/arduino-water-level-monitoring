<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find the reset token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (!$resetToken) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Check if token is valid (1 hour expiry)
        if (now()->diffInMinutes($resetToken->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
            return back()->withErrors(['token' => 'Reset token has expired.']);
        }

        // Verify token
        if (!hash_equals($resetToken->token, hash('sha256', $validated['token']))) {
            return back()->withErrors(['token' => 'Invalid token.']);
        }

        // Update password
        $user = User::where('email', $validated['email'])->first();
        $user->update(['password' => Hash::make($validated['password'])]);

        // Delete reset token
        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully!');
    }
}
