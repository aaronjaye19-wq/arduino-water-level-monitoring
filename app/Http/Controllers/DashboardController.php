<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show dashboard based on user role
     */
    public function index(): View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin-dashboard', ['user' => $user]);
        }

        return view('dashboard.user-dashboard', ['user' => $user]);
    }

    /**
     * Show admin dashboard (admin only)
     */
    public function adminDashboard(): View
    {
        return view('dashboard.admin-dashboard', ['user' => auth()->user()]);
    }

    /**
     * Show user dashboard (user only)
     */
    public function userDashboard(): View
    {
        return view('dashboard.user-dashboard', ['user' => auth()->user()]);
    }
}
