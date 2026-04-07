# Multi-Factor Authentication & Admin System Setup Guide

## Overview

This guide covers the new MFA (Multi-Factor Authentication) and Admin system features added to your Arduino Water Level Monitoring application.

## Features Implemented

### 1. Multi-Factor Authentication (MFA/2FA)
- **TOTP-Based Authentication**: Time-Based One-Time Password (6-digit code)
- **Secure Login Flow**: Email → Password → 6-digit MFA code
- **User Control**: Users can enable/disable MFA from their dashboard
- **Admin Control**: Admins can disable MFA for users if needed

### 2. Admin Role System
- **Admin Users**: Special user role with access to admin dashboard
- **404 Protection**: Unauthorized users get 404 error (not 403) when trying to access admin routes
- **User Management**: Admins can:
  - View all users and their status
  - Grant/revoke admin status
  - Disable MFA for users
  - Delete user accounts
- **System Statistics**: View total users, verified users, MFA-enabled users, and admin count

## Database Migrations

Three new migrations have been created:

### 1. `add_admin_role_to_users`
Adds two columns to the `users` table:
- `is_admin` (boolean, default: false)
- `mfa_enabled` (boolean, default: false)

### 2. `create_user_mfa_settings_table`
Stores MFA configuration for each user:
- `user_id` - Reference to user
- `secret` - TOTP secret key (base64 encoded)
- `backup_codes` - JSON array of backup codes
- `is_enabled` - Whether MFA is enabled

### 3. `create_mfa_verification_tokens_table`
Tracks temporary MFA verification attempts:
- `user_id` - Reference to user
- `code` - 6-digit OTP code
- `attempts` - Number of verification attempts (max 3)
- `expires_at` - Token expiration time

## Running Migrations

Execute the migrations to create the new database tables:

```bash
php artisan migrate
```

## Making Your First Admin User

You can create an admin user through the database or by creating an admin user via registration and then setting the `is_admin` flag:

### Option 1: Database Query
```sql
UPDATE users SET is_admin = true WHERE email = 'your-admin@example.com';
```

### Option 2: Artisan Command (Create one later)
We recommend creating an admin user through the UI first, then running the SQL command above.

## User Routes

### Authentication
- `GET /register` - Show registration form
- `POST /register` - Register new user
- `GET /login` - Show login form
- `POST /login` - Login (with MFA flow if enabled)
- `GET /email/verify/{token}` - Verify email address
- `GET /forgot-password` - Show forgot password form
- `POST /forgot-password` - Send password reset email
- `GET /reset-password/{token}` - Show reset password form
- `POST /reset-password/{token}` - Reset password
- `POST /logout` - Logout user

### MFA/2FA Routes
- `GET /mfa/verify` - Show MFA verification form (after login)
- `POST /mfa/verify` - Verify MFA code and complete login
- `GET /mfa/setup` - Show MFA setup page (authenticated users only)
- `POST /mfa/setup` - Enable MFA for user
- `POST /mfa/disable` - Disable MFA for user (authenticated users only)

### User Dashboard
- `GET /dashboard` - User dashboard (requires authentication)

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard (requires admin role)
- `GET /admin/users` - User management page (requires admin role)
- `POST /admin/users/{id}/toggle-admin` - Grant/revoke admin status
- `POST /admin/users/{id}/disable-mfa` - Disable MFA for a user
- `POST /admin/users/{id}/delete` - Delete a user account

**Note**: All admin routes return 404 for non-admin users (doesn't reveal that admin area exists)

## Login Flow with MFA

### Without MFA Enabled
1. User enters email and password on `/login`
2. Credentials validated
3. User logged in and redirected to dashboard

### With MFA Enabled
1. User enters email and password on `/login`
2. Credentials validated
3. User redirected to `/mfa/verify`
4. User opens their authenticator app and enters 6-digit code
5. Code verified (must match TOTP algorithm)
6. User logged in and redirected to dashboard

## Setting Up MFA as a User

### Step 1: Go to MFA Setup
From the dashboard, click "Enable MFA" or navigate to `/mfa/setup`

### Step 2: Download Authenticator App
Download one of these apps on your smartphone:
- **Google Authenticator** (iOS/Android)
- **Microsoft Authenticator** (iOS/Android)
- **Authy** (iOS/Android)
- **FreeOTP** (iOS/Android)

### Step 3: Add Secret to Authenticator
- Open your authenticator app
- Create a new account/add authentication
- Enter the secret key shown on the setup page, or scan the QR code
- The app will generate a 6-digit code

### Step 4: Verify and Enable
- Enter the 6-digit code from your authenticator app
- Click "Enable Two-Factor Authentication"
- MFA is now enabled for your account

## TOTP Algorithm Details

The system uses HMAC-SHA1 with a 30-second time window:

```
HMAC-SHA1(secret, current_time_step)
→ Extract 6-digit number using dynamic truncation
→ 6-digit OTP valid for 30 seconds
```

This is the standard TOTP implementation (RFC 6238).

## Admin Dashboard Features

### Overview Statistics
- **Total Users**: Count of all users in the system
- **Verified Users**: Users who completed email verification
- **MFA Enabled**: Users with MFA/2FA enabled
- **Admins**: Count of admin users

### User Management
The admin users page displays:
- User name and email
- Verification status
- Admin status badge
- MFA status

### Admin Actions
For each user, admins can:
1. **Toggle Admin Status**: Grant or revoke admin role
   - Prevents self-removal from admin
2. **Disable MFA**: Remove MFA from user account
   - Useful if user loses access to authenticator
3. **Delete User**: Permanently remove user
   - Prevents self-deletion

## Middleware

Two middleware have been added:

### CheckAdminRole
- Applied to all admin routes
- Checks if user is authenticated
- Checks if user has `is_admin = true`
- Returns 404 (not 403) for unauthorized users

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes here
});
```

### CheckMfa
- Applied to protected routes when needed
- Checks if user has MFA enabled
- Redirects to MFA verification if not verified
- Stores `mfa_verified` flag in session

## Models & Relationships

### User Model
```php
$user->mfaSetting()        // HasOne UserMfaSetting
$user->mfaVerificationTokens() // HasMany MfaVerificationToken
```

### UserMfaSetting Model
- Methods:
  - `generateTotpCode()` - Generate current 6-digit code
  - `verifyCode($code)` - Verify user-provided code

### MfaVerificationToken Model
- Methods:
  - `isExpired()` - Check if token has expired
  - `hasExceededAttempts()` - Check if 3+ attempts made
  - `incrementAttempt()` - Add failed attempt

## Security Features

1. **Email Verification Required**: Users must verify email before login
2. **Password Hashing**: bcrypt with Laravel's Hash facade
3. **TOTP-Based 2FA**: Industry-standard 30-second time window
4. **Attempt Limiting**: Max 3 failed MFA attempts per token
5. **Token Expiration**: MFA codes valid for 30 seconds
6. **CSRF Protection**: All forms protected by CSRF tokens
7. **Session-Based Auth**: Secure session handling
8. **404 Masking**: Admins return 404 to prevent route enumeration

## Troubleshooting

### MFA Code Not Working
- Check that device time is synchronized
- Verify the secret was entered correctly
- Ensure authenticator app is showing current code
- Try the next code (they change every 30 seconds)

### Lost Access to Authenticator
- Admin can disable MFA via `/admin/users`
- User can contact admin for MFA reset
- After MFA disabled, user can set up new authenticator

### Admin Can't Access Admin Dashboard
- Verify `is_admin` is set to `true` in database
- Check user is authenticated (logged in)
- Make sure middleware is registered in `bootstrap/app.php`

### 404 Instead of Admin Dashboard
- This is intentional - admins return 404 for security
- User is not admin (correct behavior)
- Check database to verify admin status

## Next Steps

1. **Run migrations**: `php artisan migrate`
2. **Create first admin**: `UPDATE users SET is_admin = true WHERE email = 'your-email@example.com'`
3. **Test login flow**: Register, verify email, login
4. **Enable MFA**: Go to `/mfa/setup` on dashboard
5. **Manage users**: Login as admin, visit `/admin/dashboard`

## Environment Configuration

All MFA features use default Laravel configuration. No additional `.env` variables needed beyond existing mail configuration for email verification and password reset.

## Architecture Overview

```
Login Form
    ↓
Email + Password Validation
    ↓
Is MFA Enabled?
    ├─ NO → Login User → Dashboard
    └─ YES → Generate TOTP Code → MFA Verify Form
                    ↓
            User Enters 6-Digit Code
                    ↓
            Verify Against Current TOTP
                    ↓
            Success? → Login User → Dashboard
            Failure? → Show Error, Allow Retry (max 3)
```

## Support

For issues or questions regarding MFA or admin features:
1. Check the troubleshooting section above
2. Review database migrations
3. Verify all routes are registered in `/routes/web.php`
4. Check middleware is registered in `/bootstrap/app.php`
