# Authentication System Documentation

## Overview

This Laravel application includes a comprehensive authentication system with the following features:

1. **Secure User Registration and Login**
2. **Email Verification** - Users must verify their email before accessing the dashboard
3. **Multi-Factor Authentication (MFA)** - 6-digit code sent via email
4. **Password Recovery** - Token-based password reset with 60-second expiration
5. **Role-Based Access Control (RBAC)** - Admin and User roles

## Features

### 1. User Registration

**Route:** `GET/POST /auth/register`

Users can create a new account with:
- Full name
- Email address
- Password (minimum 8 characters)

After registration:
- A verification email is sent (implement email sending in AuthController)
- User is redirected to email verification page
- User cannot login until email is verified

**Files:**
- Controller: `app/Http/Controllers/AuthController.php` - `register()` method
- View: `resources/views/auth/register.blade.php`

### 2. Email Verification

**Routes:**
- `GET /auth/verify-email/{userId}` - Show verification notice
- `GET /auth/verify-email?token=...&user_id=...` - Verify email token

Process:
1. User receives verification email with token link
2. Token expires in 24 hours
3. Upon verification, `email_verified_at` is set
4. User can now login

**Files:**
- Controller: `app/Http/Controllers/AuthController.php`
- Database: `database/migrations/2024_01_01_000005_create_email_verifications_table.php`
- Model: `app/Models/EmailVerification.php`

### 3. User Login

**Route:** `GET/POST /auth/login`

Login process:
1. User enters email and password
2. Credentials are validated
3. Email verification is checked
4. MFA code is generated (6-digit)
5. User is redirected to MFA verification

**Files:**
- Controller: `app/Http/Controllers/AuthController.php` - `login()` method
- View: `resources/views/auth/login.blade.php`

### 4. Multi-Factor Authentication (MFA)

**Routes:**
- `GET /auth/mfa-verify` - Show MFA verification form
- `POST /auth/mfa-verify` - Verify MFA code

MFA Process:
1. After login, user receives 6-digit code (logged in console for development)
2. Code expires in 5 minutes
3. User enters code in the MFA form
4. If valid, user is authenticated and redirected to dashboard
5. If invalid, error is displayed

**Database Columns Added:**
- `mfa_code` - The 6-digit code
- `mfa_code_expires_at` - Code expiration timestamp
- `mfa_verified` - Whether MFA was verified

**Files:**
- Controller: `app/Http/Controllers/AuthController.php`
- View: `resources/views/auth/mfa-verify.blade.php`
- Migration: `database/migrations/2024_01_01_000003_add_mfa_to_users_table.php`

### 5. Password Recovery

**Routes:**
- `GET /auth/forgot-password` - Show password reset request form
- `POST /auth/forgot-password` - Send password reset link
- `GET /auth/reset-password?token=...&email=...` - Show password reset form
- `POST /auth/reset-password` - Reset password

Password Reset Process:
1. User requests password reset by entering email
2. A token is generated and stored in `password_reset_tokens` table
3. Token expires in **60 seconds** (as per requirements)
4. User receives reset link via email
5. User clicks link and enters new password
6. Password is updated in database
7. Token is deleted

**Database Table:**
```sql
password_reset_tokens
- email (primary key)
- token
- created_at
- expires_at (60 second expiration)
```

**Files:**
- Controller: `app/Http/Controllers/AuthController.php`
- Views: 
  - `resources/views/auth/forgot-password.blade.php`
  - `resources/views/auth/reset-password.blade.php`
- Migration: `database/migrations/2024_01_01_000004_create_password_reset_tokens_table.php`

### 6. Role-Based Access Control (RBAC)

**User Roles:**
- `admin` - Administrator with access to admin dashboard
- `user` - Regular user with access to user dashboard

**Routes:**
- `GET /dashboard` - Redirects to appropriate dashboard based on role
- `GET /dashboard/admin` - Admin dashboard (requires `role:admin`)
- `GET /dashboard/user` - User dashboard (requires `role:user`)

**Middleware:** `App\Http\Middleware\CheckRole`

**Usage in Routes:**
```php
Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])
    ->middleware('role:admin')
    ->name('dashboard.admin');
```

**Files:**
- Middleware: `app/Http/Middleware/CheckRole.php`
- Controller: `app/Http/Controllers/DashboardController.php`
- Views:
  - `resources/views/dashboard/admin-dashboard.blade.php`
  - `resources/views/dashboard/user-dashboard.blade.php`

## User Model

The `User` model (`app/Models/User.php`) includes:

**New Attributes:**
- `role` - User role (admin/user)
- `mfa_code` - Current MFA code
- `mfa_code_expires_at` - MFA code expiration
- `mfa_verified` - MFA verification status
- `email_verified_at` - Email verification timestamp

**Helper Methods:**
- `isAdmin()` - Check if user is admin
- `isUser()` - Check if user is regular user
- `isEmailVerified()` - Check if email is verified
- `generateMfaCode()` - Generate 6-digit MFA code
- `verifyMfaCode($code)` - Verify MFA code

## Database Schema

### Users Table Changes

Added columns to existing `users` table:
```sql
ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT 'user';
ALTER TABLE users ADD COLUMN mfa_code VARCHAR(6) NULL;
ALTER TABLE users ADD COLUMN mfa_code_expires_at TIMESTAMP NULL;
ALTER TABLE users ADD COLUMN mfa_verified BOOLEAN DEFAULT FALSE;
```

### New Tables

**email_verifications**
```sql
- id
- user_id (foreign key)
- token
- expires_at
- created_at
```

**password_reset_tokens**
```sql
- email (primary key)
- token
- created_at
- expires_at
```

## Running Migrations

To set up the database:

```bash
php artisan migrate
```

This will:
1. Create the base users table
2. Add MFA columns to users table
3. Create email_verifications table
4. Create password_reset_tokens table

## Implementation Notes

### Email Sending (Development)

In development, MFA codes and password reset links are logged to the console. To implement real email sending:

1. **Configure mail in `.env`:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@example.com
```

2. **Create Mail classes:**
```bash
php artisan make:mail SendMfaCode
php artisan make:mail SendPasswordResetLink
php artisan make:mail SendEmailVerificationLink
```

3. **Update AuthController** to send emails:
```php
Mail::send('emails.mfa-code', ['code' => $code], function ($message) use ($user) {
    $message->to($user->email)->subject('Your MFA Code');
});
```

### Session Management

- MFA verification stores `auth_pending_user_id` in session
- After MFA verification, session is cleared
- Logout invalidates session and regenerates token

## Security Considerations

1. **Password Hashing** - Uses Laravel's bcrypt hashing
2. **CSRF Protection** - All POST requests are protected by CSRF tokens
3. **Token Expiration**:
   - Email verification: 24 hours
   - MFA code: 5 minutes
   - Password reset: 60 seconds
4. **Session Security** - HTTP-only cookies for session storage
5. **Input Validation** - All inputs are validated server-side

## Testing the System

### Create Admin User (via tinker)

```bash
php artisan tinker
```

```php
$user = User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);
```

### Test MFA Code

MFA codes are logged in storage/logs/laravel.log. Look for:
```
MFA Code for user admin@example.com: 123456
```

### Test Password Reset

Password reset links are logged in storage/logs/laravel.log. Look for:
```
Password reset link: http://localhost:8000/auth/reset-password?token=...&email=...
```

## Routes Summary

| Method | Route | Name | Description |
|--------|-------|------|-------------|
| GET | /auth/register | auth.register | Show registration form |
| POST | /auth/register | auth.register.store | Handle registration |
| GET | /auth/verify-email/{userId} | auth.verify-email-notice | Email verification notice |
| GET | /auth/verify-email | auth.verify-email | Verify email token |
| GET | /auth/login | auth.login | Show login form |
| POST | /auth/login | auth.login.store | Handle login |
| GET | /auth/mfa-verify | auth.mfa-verify | Show MFA form |
| POST | /auth/mfa-verify | auth.mfa-verify.store | Verify MFA code |
| GET | /auth/forgot-password | auth.forgot-password | Show forgot password form |
| POST | /auth/forgot-password | auth.forgot-password.store | Send reset link |
| GET | /auth/reset-password | auth.reset-password | Show reset password form |
| POST | /auth/reset-password | auth.reset-password.store | Reset password |
| GET | /dashboard | - | Show appropriate dashboard |
| POST | /auth/logout | auth.logout | Logout user |

## Files Created/Modified

### Created Files:
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Middleware/CheckRole.php`
- `app/Models/EmailVerification.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/verify-email-notice.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/mfa-verify.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/dashboard/user-dashboard.blade.php`
- `resources/views/dashboard/admin-dashboard.blade.php`
- `database/migrations/2024_01_01_000003_add_mfa_to_users_table.php`
- `database/migrations/2024_01_01_000004_create_password_reset_tokens_table.php`
- `database/migrations/2024_01_01_000005_create_email_verifications_table.php`

### Modified Files:
- `app/Models/User.php` - Added MFA and role support
- `routes/web.php` - Added authentication routes
- `bootstrap/app.php` - Registered role middleware
