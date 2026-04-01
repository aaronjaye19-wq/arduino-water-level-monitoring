# Quick Start Guide - Authentication System

## Prerequisites
- Laravel 12 installed
- SQLite or MySQL configured in `.env`

## Setup Steps

### Option 1: Using Artisan Command (Recommended)
```bash
php artisan app:setup
```

This command will:
- Run all migrations
- Create an admin user (admin@example.com / password123)
- Create a test user (user@example.com / password123)

### Option 2: Using HTTP Routes (For Development)

1. **Clear all caches:**
   ```
   http://localhost:8000/clear-cache
   ```

2. **Run database migrations:**
   ```
   http://localhost:8000/setup/migrate
   ```

3. **Create admin user:**
   ```
   http://localhost:8000/setup/seed-admin
   ```

4. **Clear caches again:**
   ```
   http://localhost:8000/setup/clear-cache-all
   ```

### Option 3: Manual Setup
```bash
# Run migrations
php artisan migrate

# Create admin user via tinker
php artisan tinker
```

Then in tinker:
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
    'email_verified_at' => now(),
    'mfa_verified' => true,
]);
```

## Access the Application

### Default Credentials

**Admin Account:**
- Email: `admin@example.com`
- Password: `password123`
- Role: `admin`

**Test User Account:**
- Email: `user@example.com`
- Password: `password123`
- Role: `user`

### Application URLs

- **Home:** `http://localhost:8000/`
- **Register:** `http://localhost:8000/auth/register`
- **Login:** `http://localhost:8000/auth/login`
- **Admin Dashboard:** `http://localhost:8000/dashboard/admin` (admin only)
- **User Dashboard:** `http://localhost:8000/dashboard/user` (user only)
- **Logout:** `http://localhost:8000/logout`

## Features Overview

### ✅ Secure Authentication
- Email and password validation
- Password hashing with bcrypt

### ✅ Email Verification
- Users must verify email before accessing dashboard
- 24-hour token expiration
- Verification token stored in database

### ✅ Multi-Factor Authentication (MFA)
- 6-digit verification code
- 5-minute code expiration
- Code sent to user's email (logs for development)

### ✅ Password Recovery
- Token-based password reset
- 60-second token expiration
- Secure token validation

### ✅ Role-Based Access Control
- Two roles: `admin` and `user`
- Separate dashboards for each role
- Middleware-protected routes

## Development Notes

### Viewing Logs
During development, check Laravel logs for:
- MFA codes: `/storage/logs/laravel.log`
- Password reset links: `/storage/logs/laravel.log`

### Database
- Uses SQLite by default (database.sqlite)
- All tables created in single migration
- Foreign keys with cascade delete

### Email
Currently, email functionality logs to the console/logs. To enable real email:
1. Configure mail settings in `.env`
2. Uncomment Mail::send() calls in AuthController

## Troubleshooting

**500 Error on home page?**
1. Run: `/setup/clear-cache-all`
2. Run: `/setup/migrate`
3. Run: `/setup/seed-admin`
4. Refresh the page

**MFA code not received?**
- Check `/storage/logs/laravel.log` for the generated code
- Currently logs to console for development

**Can't login after registering?**
- Verify your email first by clicking the verification link in the logs
- Or create a pre-verified account using `/setup/seed-admin`

**Database locked errors?**
- Delete `database.sqlite` if using SQLite
- Run migrations again: `php artisan migrate`

## Security Notes

⚠️ **Before Production:**
1. Change default credentials
2. Implement real email sending (not logged)
3. Add HTTPS requirement
4. Implement rate limiting on login attempts
5. Add CAPTCHA for registration
6. Remove `/setup/*` routes
7. Add proper logging and monitoring
8. Implement session timeout
9. Add password strength validation
10. Implement 2FA device trust/remember me option

## File Structure

```
app/
├── Console/Commands/SetupApp.php
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   └── DashboardController.php
│   └── Middleware/
│       └── CheckRole.php
└── Models/
    ├── User.php
    └── EmailVerification.php

database/migrations/
└── 0001_01_01_000000_create_users_table.php

resources/views/
├── auth/
│   ├── register.blade.php
│   ├── login.blade.php
│   ├── verify-email-notice.blade.php
│   ├── mfa-verify.blade.php
│   ├── forgot-password.blade.php
│   └── reset-password.blade.php
└── dashboard/
    ├── admin-dashboard.blade.php
    └── user-dashboard.blade.php
```
