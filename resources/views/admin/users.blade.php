<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>
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
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 28px;
            color: #000;
        }
        .back-link {
            color: #000;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 12px;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .back-link:hover {
            background-color: #f5f5f5;
            border-color: #000;
        }
        .section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table thead {
            background-color: #f5f5f5;
            border-bottom: 2px solid #e5e5e5;
        }
        .table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e5e5e5;
            font-size: 14px;
        }
        .table tbody tr:hover {
            background-color: #fafafa;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-admin {
            background-color: #f5f5f5;
            color: #000;
            border: 1px solid #d0d0d0;
        }
        .badge-verified {
            background-color: #f1f8f4;
            color: #2e7d32;
        }
        .badge-mfa {
            background-color: #f1f8f4;
            color: #2e7d32;
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .action-btn {
            background-color: #000;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s;
            white-space: nowrap;
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
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>User Management</h2>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="container">
        <div class="header">
            <h1>Manage Users</h1>
            <a href="{{ route('admin.dashboard') }}" class="back-link">← Back to Dashboard</a>
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

        <div class="section">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Admin</th>
                        <th>MFA</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->is_verified)
                                    <span class="badge badge-verified">✓ Verified</span>
                                @else
                                    <span style="color: #999; font-size: 13px;">Not Verified</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->is_admin)
                                    <span class="badge badge-admin">Admin</span>
                                @else
                                    <span style="color: #999; font-size: 13px;">User</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->mfa_enabled)
                                    <span class="badge badge-mfa">Enabled</span>
                                @else
                                    <span style="color: #999; font-size: 13px;">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <form action="{{ route('admin.toggle-admin', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn">
                                            {{ $user->is_admin ? 'Revoke Admin' : 'Grant Admin' }}
                                        </button>
                                    </form>

                                    @if ($user->mfa_enabled)
                                        <form action="{{ route('admin.disable-mfa', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn">Disable MFA</button>
                                        </form>
                                    @endif

                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.delete-user', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            <button type="submit" class="action-btn danger">Delete User</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999; padding: 30px;">
                                No users found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="pagination">
                    @if ($users->onFirstPage())
                        <span style="color: #ccc;">← Previous</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" style="text-decoration: none; color: #000;">← Previous</a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <strong>{{ $page }}</strong>
                        @else
                            <a href="{{ $url }}" style="text-decoration: none; color: #000;">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" style="text-decoration: none; color: #000;">Next →</a>
                    @else
                        <span style="color: #ccc;">Next →</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>
