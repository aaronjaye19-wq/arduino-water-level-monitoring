<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: #fafafa;
            color: #333;
        }
        .navbar {
            background-color: #000;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e5e5;
        }
        .navbar h2 { margin: 0; font-size: 22px; }
        .navbar .logout-btn {
            background-color: #666;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
        }
        .navbar .logout-btn:hover { background-color: #444; }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        .header {
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #000;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .stat-card h3 {
            color: #666;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #000;
        }
        .section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .section h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #000;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 15px;
        }
        .action-btn {
            background-color: #000;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .action-btn:hover {
            background-color: #222;
        }
        .action-btn.danger {
            background-color: #d32f2f;
        }
        .action-btn.danger:hover {
            background-color: #b71c1c;
        }
        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #f1f8f4;
            border: 1px solid #c8e6c9;
            color: #2e7d32;
        }
        .alert-error {
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            color: #d32f2f;
        }
        .link-btn {
            display: inline-block;
            color: #000;
            text-decoration: none;
            font-weight: 600;
            margin-right: 10px;
            padding: 8px 12px;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .link-btn:hover {
            background-color: #f5f5f5;
            border-color: #000;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Admin Dashboard</h2>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="container">
        <div class="header">
            <h1>System Overview</h1>
            <p style="color: #666;">Welcome back, {{ auth()->user()->name }}. Manage your system here.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number">{{ $totalUsers }}</div>
            </div>
            <div class="stat-card">
                <h3>Verified Users</h3>
                <div class="number">{{ $verifiedUsers }}</div>
            </div>
            <div class="stat-card">
                <h3>MFA Enabled</h3>
                <div class="number">{{ $mfaUsers }}</div>
            </div>
            <div class="stat-card">
                <h3>Admins</h3>
                <div class="number">{{ $adminUsers }}</div>
            </div>
        </div>

        <div class="section">
            <h2>Quick Actions</h2>
            <p style="margin-bottom: 20px; color: #666;">
                <a href="{{ route('admin.users') }}" class="link-btn">Manage Users</a>
            </p>
            <p style="color: #666; font-size: 13px;">
                View and manage all users, toggle admin status, disable MFA, or remove users from the system.
            </p>
        </div>
    </div>
</body>
</html>
