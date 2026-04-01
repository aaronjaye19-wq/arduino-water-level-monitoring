# Authentication & Admin Dashboard System Setup

## Overview

This document outlines the authentication system, email verification, password reset functionality, and admin dashboard that have been added to the Arduino Water Level Monitoring project.

## System Architecture

### Services (JSON File-Based Storage)

All user data is stored in JSON files in the `storage/app/users/` directory:

- **UserStorageService** (`app/Services/UserStorageService.php`)
  - Manages user creation, retrieval, and updates
  - Stores users in `storage/app/users/users.json`
  - Handles password hashing with bcrypt

- **VerificationService** (`app/Services/VerificationService.php`)
  - Generates 6-digit email verification codes
  - Manages code expiration (15 minutes)
  - Stores codes in `storage/app/users/verification_codes.json`

- **PasswordResetService** (`app/Services/PasswordResetService.php`)
  - Generates secure reset tokens
  - Manages token expiration (1 hour)
  - Stores tokens in `storage/app/users/password_resets.json`

### Authentication Flow

#### Registration → Email Verification → Login

1. **User Registration** (`/register`)
   - User provides: Name, Email, Password
   - System creates user account (email_verified = false)
   - Generates 6-digit verification code
   - Displays code for user to enter

2. **Email Verification** (`/verify-email`)
   - User enters 6-digit code
   - System validates code against stored code
   - Sets email_verified = true on successful verification
   - Logs user in automatically
   - Redirects to dashboard

3. **Login** (`/login`)
   - User provides: Email, Password
   - System validates credentials
   - Checks if email is verified
   - If not verified, redirects to verification page
   - Sets session variables and creates session cookie

#### Forgot Password Flow

1. **Request Reset** (`/forgot-password`)
   - User enters email address
   - System generates reset token (64-char random string)
   - Token valid for 1 hour
   - Redirects to reset password page

2. **Reset Password** (`/reset-password`)
   - User enters new password
   - System validates token
   - Updates password hash
   - Consumes token (prevents reuse)
   - Redirects to login

## Default Admin Account

**Pre-created admin account (auto-generated on first run):**

```
Email:    admin@waterflow.local
Password: Admin@123456
Role:     admin
```

## User Roles

Two roles are supported:

- **admin**: Full access to dashboard, measurements history, user management
- **user**: Limited access to dashboard (no measurements history)

## File Structure

```
app/
├── Services/
│   ├── UserStorageService.php
│   ├── VerificationService.php
│   └── PasswordResetService.php
├── Http/
│   ├── Controllers/
│   │   └── AuthController.php
│   └── Middleware/
│       ├── AuthenticateSession.php
│       └── InitializeStorage.php

resources/views/
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── verify-email.blade.php
│   ├── forgot-password.blade.php
│   └── reset-password.blade.php
└── dashboard.blade.php

storage/app/users/
├── users.json
├── verification_codes.json
└── password_resets.json
```

## Routes

### Public Routes (No Authentication Required)

```
GET  /              → Home (redirects to login if not authenticated)
GET  /login         → Login form
POST /login         → Process login
GET  /register      → Registration form
POST /register      → Process registration
GET  /verify-email  → Email verification form
POST /verify-email  → Process verification
GET  /forgot-password   → Forgot password form
POST /forgot-password   → Generate reset token
GET  /reset-password    → Reset password form
POST /reset-password    → Process password reset
POST /logout        → Logout (requires session)
```

### Protected Routes (Require Authentication)

```
GET /dashboard      → Admin/User Dashboard
```

### API Routes (Existing)

```
POST /api/sensor        → Receive sensor data from Arduino
GET  /api/latest-sensor → Get latest sensor reading
```

## Session Management

Sessions are stored using Laravel's session system:

- Session variables set on login:
  - `authenticated`: Boolean flag (true when logged in)
  - `user_id`: User ID from users.json
  - `user_email`: User email address
  - `user_name`: User full name
  - `user_role`: User role (admin or user)

- Middleware `auth.session` checks these variables on protected routes
- Sessions automatically cleared on logout

## Email System

**Mock Email System**: Currently uses console output for demonstration

When a user:
- Registers: Verification code is displayed in the UI and console
- Requests password reset: Reset token is displayed in the UI and console

**Note**: In production, integrate with Gmail API or email service by modifying:
- `app/Mail/VerificationCodeMail.php` (create if using real email)
- `app/Mail/PasswordResetMail.php` (create if using real email)

## Dashboard Features

### For All Users
- Live water level tank visualization
- Real-time status lights (green/yellow/red)
- Current, maximum, and minimum level statistics
- System status indicator

### For Admin Users Only
- Water level measurements history (last 10 readings)
- Status badges (Low/Medium/High)
- Timestamp for each measurement
- Detailed metrics table

## Security Considerations

1. **Passwords**: Bcrypt hashing with Laravel's Hash facade
2. **Tokens**: 64-character random strings for password resets
3. **Verification Codes**: 6-digit codes with 15-minute expiration
4. **CSRF Protection**: Enabled on all forms
5. **Session Security**: HTTP-only cookies (Laravel default)

## Testing the System

### Test Flow

1. **Fresh Start**
   - Application auto-creates `admin@waterflow.local` with password `Admin@123456`
   - Navigate to `http://localhost/`
   - Redirected to login page

2. **Login as Admin**
   - Email: `admin@waterflow.local`
   - Password: `Admin@123456`
   - See full dashboard with measurements history

3. **Register New User**
   - Click "Create Account" link
   - Fill in name, email, password
   - Enter 6-digit code shown on screen
   - Automatically logged in, redirected to dashboard

4. **Test Password Reset**
   - Logout
   - Click "Forgot Password?"
   - Enter email
   - Follow reset link/token
   - Set new password
   - Login with new password

## Customization

### Add Role-Based Features
Edit `/dashboard.blade.php`:
```blade
@if (session('user_role') === 'admin')
    <!-- Show admin-only content -->
@endif
```

### Change Verification Code Expiration
Edit `app/Services/VerificationService.php`, line in `generateCode()`:
```php
'expires_at' => now()->addMinutes(15)->toDateTimeString(), // Change 15 to desired minutes
```

### Change Password Reset Token Expiration
Edit `app/Services/PasswordResetService.php`, line in `generateToken()`:
```php
'expires_at' => now()->addHour()->toDateTimeString(), // Change duration as needed
```

### Modify Default Admin Credentials
Edit `app/Services/UserStorageService.php`, in `initializeDefaultUsers()`:
```php
[
    'email' => 'admin@example.com',
    'password' => Hash::make('YourPassword123'),
    // ...
]
```

## Troubleshooting

### Issue: User files not created
**Solution**: Files are auto-created on first application request. If needed, manually create:
- Create directory: `storage/app/users/`
- Service classes will auto-create JSON files

### Issue: "Invalid session" errors
**Solution**: Clear session/cookies and try logging in again

### Issue: "Email already registered"
**Solution**: Check `storage/app/users/users.json` for the email, or register with different email

### Issue: Verification code expired
**Solution**: Codes expire in 15 minutes. Request new registration to get new code

## Integration with Arduino

The existing Arduino sensor integration still works:

```
Arduino → POST /api/sensor
         {sensor: 150, green: true, yellow: false, red: false}

Dashboard ← GET /api/latest-sensor
          {sensor: 150, green: true, yellow: false, red: false}
```

Water level readings automatically display in the tank visualization and measurements table (admin only).

## Next Steps

1. Test the authentication system thoroughly
2. Customize admin credentials if needed
3. Integrate real email service for production use
4. Add additional admin features as needed
5. Deploy to Vercel or hosting platform
