<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Get all users for admin dashboard
        $users = User::all();
        $totalUsers = User::count();
        $verifiedUsers = User::where('is_verified', true)->count();
        $mfaUsers = User::where('mfa_enabled', true)->count();
        $adminUsers = User::where('is_admin', true)->count();

        return view('admin.dashboard', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'verifiedUsers' => $verifiedUsers,
            'mfaUsers' => $mfaUsers,
            'adminUsers' => $adminUsers,
        ]);
    }

    /**
     * Show user management page
     */
    public function userManagement()
    {
        $users = User::paginate(10);
        return view('admin.users', ['users' => $users]);
    }

    /**
     * Toggle admin status for a user
     */
    public function toggleAdmin($userId)
    {
        $user = User::findOrFail($userId);
        
        // Prevent self-removal from admin
        if ($user->id === auth()->id() && $user->is_admin) {
            return redirect()->back()->with('error', 'You cannot remove your own admin status');
        }

        $user->update(['is_admin' => !$user->is_admin]);
        
        $action = $user->is_admin ? 'granted' : 'revoked';
        return redirect()->back()->with('success', "Admin status {$action} for {$user->name}");
    }

    /**
     * Disable MFA for a user
     */
    public function disableMfa($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['mfa_enabled' => false]);
        $user->mfaSetting()->update(['is_enabled' => false]);

        return redirect()->back()->with('success', "MFA disabled for {$user->name}");
    }

    /**
     * Delete a user
     */
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "User {$userName} has been deleted");
    }
}
