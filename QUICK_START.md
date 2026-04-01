# Quick Start Guide - Authentication & Dashboard

## What Was Added

✅ **User Authentication System**
- Registration with email verification
- 6-digit OTP email verification (mock system)
- Login with role-based access control
- Logout functionality

✅ **Password Reset**
- Forgot password functionality
- Secure reset token (1 hour expiration)
- Mock email system displays tokens

✅ **User Roles**
- **Admin Role**: Full dashboard access + measurements history
- **User Role**: Limited dashboard access

✅ **Default Admin Account**
- Email: `admin@waterflow.local`
- Password: `Admin@123456`

✅ **Enhanced Dashboard**
- Modern UI with gradient design
- User profile display with logout
- Real-time water level visualization
- Statistics cards (current, max, min levels)
- Admin-only measurements history table
- Responsive design

✅ **JSON File Storage**
- No database required
- User data stored in `storage/app/users/users.json`
- Verification codes stored in `storage/app/users/verification_codes.json`
- Password resets stored in `storage/app/users/password_resets.json`

## Quick Test Steps

### Step 1: Start the Application
```bash
npm run dev    # or the command specified in your dev environment
```

### Step 2: Navigate to Application
```
http://localhost:8000/
```
You will be automatically redirected to the login page.

### Step 3: Login as Admin
- Email: `admin@waterflow.local`
- Password: `Admin@123456`
- Click "Login"
- You should see the dashboard with measurements history

### Step 4: Logout
Click the "Logout" button in the top right of the dashboard.

### Step 5: Test Registration
- Click "Create Account"
- Fill in:
  - Full Name: `Test User`
  - Email: `test@example.com`
  - Password: `Test@123456` (min 6 chars)
  - Confirm Password: `Test@123456`
- Click "Create Account"
- On the next screen, you'll see a 6-digit code
- Enter that code and click "Verify Email"
- You'll be logged in automatically and see the dashboard

### Step 6: Test Password Reset
- Click "Logout"
- Click "Forgot Password?"
- Enter the email address
- Click "Send Reset Link"
- You'll see a page to reset password
- Enter new password and confirm
- Click "Reset Password"
- Login with your new password

## File Locations

### Authentication Files
- Controllers: `app/Http/Controllers/AuthController.php`
- Services: `app/Services/`
  - `UserStorageService.php`
  - `VerificationService.php`
  - `PasswordResetService.php`

### View Files (Frontend)
- Login: `resources/views/auth/login.blade.php`
- Register: `resources/views/auth/register.blade.php`
- Verify Email: `resources/views/auth/verify-email.blade.php`
- Forgot Password: `resources/views/auth/forgot-password.blade.php`
- Reset Password: `resources/views/auth/reset-password.blade.php`
- Dashboard: `resources/views/dashboard.blade.php`

### Configuration
- Routes: `routes/web.php`
- Middleware: `app/Http/Middleware/`
  - `AuthenticateSession.php`
  - `InitializeStorage.php`

### Data Storage
- Users: `storage/app/users/users.json`
- Codes: `storage/app/users/verification_codes.json`
- Tokens: `storage/app/users/password_resets.json`

## Features by Role

### For All Users
✓ Login/Registration
✓ Email verification
✓ Password reset
✓ View real-time water level
✓ View status lights
✓ View statistics (current, max, min)

### For Admin Users Only
✓ View measurements history
✓ View water level status badges
✓ Access to all user data (in measurements table)

## Important Notes

1. **Verification Codes**: Expire after 15 minutes. The code is displayed on the page.

2. **Password Reset Tokens**: Expire after 1 hour. When a user requests a reset, they'll see a form to reset their password.

3. **No Database Required**: All data is stored in JSON files. The system automatically creates these files on first use.

4. **Session-Based Auth**: Uses Laravel's session system with secure HTTP-only cookies.

5. **Mock Email System**: In the current setup, verification codes and reset tokens are displayed in the UI. For production, integrate with Gmail API or another email service.

## Customization Options

### Change Admin Credentials
Edit `app/Services/UserStorageService.php`:
```php
private function initializeDefaultUsers()
{
    $defaultUsers = [
        [
            'email' => 'your-email@example.com',
            'password' => Hash::make('YourPassword123'),
            // ...
        ]
    ];
}
```

### Change Verification Code Expiration
Edit `app/Services/VerificationService.php`:
```php
'expires_at' => now()->addMinutes(30)->toDateTimeString(), // Change 15 to 30
```

### Change Password Reset Expiration
Edit `app/Services/PasswordResetService.php`:
```php
'expires_at' => now()->addHours(2)->toDateTimeString(), // Change 1 to 2 hours
```

## Troubleshooting

**Q: I see "Cannot access session" error**
A: Clear your browser cookies and restart the application.

**Q: Verification code is expired**
A: Register again to get a new code. Codes are valid for 15 minutes.

**Q: "Email already registered" error**
A: That email is already registered. Use a different email or reset the `storage/app/users/users.json` file.

**Q: Dashboard doesn't load**
A: Make sure you're logged in. Check that the session is active by looking at the "Logout" button.

## What's Next?

1. Test all authentication flows thoroughly
2. Integrate with real email service (Gmail, SendGrid, Mailgun, etc.)
3. Add user management admin panel
4. Add water level alerts/notifications
5. Add data export functionality
6. Deploy to Vercel: `vercel deploy`

## Support

For detailed information about the system architecture, see `AUTHENTICATION_SETUP.md`.

For issues or questions:
1. Check browser console for JavaScript errors
2. Review Laravel logs if available
3. Verify JSON files exist in `storage/app/users/`
4. Ensure routes are accessible at `/login`, `/register`, etc.

---

**System Ready!** 🚀 Your authentication system is ready to use. Start by testing the admin login with the credentials above.
