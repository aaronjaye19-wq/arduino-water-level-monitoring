# Authentication System Setup Guide

## Overview
This Laravel application now includes a complete authentication system with:
- User registration with email verification
- Login system with verified email requirement
- Password recovery with token-based reset links (10-minute expiration)
- Protected dashboard accessible only to authenticated users

## Features

### 1. User Registration
- New users can register with name, email, and password
- Confirmation password validation
- Unique email validation
- Email verification is required before accessing the dashboard

### 2. Email Verification
- Upon registration, users receive a verification email
- Email contains a unique verification link
- Once clicked, the user's email is marked as verified
- Users can only login after email verification

### 3. Login System
- Users must enter email and password
- Email must be verified before login is allowed
- Sessions are managed securely
- Dashboard is only accessible to authenticated users

### 4. Password Recovery
- Users can request a password reset from the login page
- A reset link is sent to their registered email
- Reset links expire after 10 minutes for security
- Users can set a new password using the reset link

## Routes

```
GET  /register                      - Show registration form
POST /register                      - Submit registration
GET  /login                         - Show login form
POST /login                         - Submit login
GET  /email/verify/{token}          - Verify email (from email link)
GET  /forgot-password               - Show forgot password form
POST /forgot-password               - Send reset link email
GET  /reset-password/{token}        - Show reset password form
POST /reset-password/{token}        - Submit new password
POST /logout                        - Logout user (requires auth)
GET  /dashboard                     - Dashboard (requires auth)
```

## Database Schema

### New Tables Created:

#### email_verification_tokens
- `id` - Primary key
- `user_id` - Foreign key to users table
- `token` - Unique verification token
- `created_at` - Timestamp

#### password_reset_token_details
- `id` - Primary key
- `user_id` - Foreign key to users table
- `token` - Unique reset token
- `created_at` - Creation timestamp
- `expires_at` - Expiration timestamp (10 minutes from creation)

### Updated Tables:

#### users
- `is_verified` - Boolean field (default: false) to track email verification status

## Email Configuration

The system uses Laravel's mail configuration. By default, it's set to 'log' mode. To enable actual email sending:

### Option 1: SMTP (Gmail)
Update your `.env` file:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Water Sensor Dashboard"
```

### Option 2: SendGrid
Update your `.env` file:
```
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your-sendgrid-api-key
MAIL_FROM_ADDRESS=your-email@example.com
MAIL_FROM_NAME="Water Sensor Dashboard"
```

### Option 3: Other Services
Laravel supports many mail drivers. Check the configuration in `config/mail.php`

## Installation Instructions

1. **Run migrations:**
   ```bash
   php artisan migrate
   ```
   This creates the necessary tables and columns.

2. **Configure mail (Optional - for email testing):**
   Update `.env` file with your mail service configuration.

3. **Test the system:**
   - Navigate to `/register` and create a new account
   - Check your mail logs or email inbox for verification link
   - Click the verification link
   - Login with your credentials
   - Access the dashboard

## File Structure

```
app/
  ├── Http/Controllers/AuthController.php     - Authentication logic
  ├── Models/
  │   ├── User.php                            - Updated with relationships
  │   ├── EmailVerificationToken.php           - Email verification model
  │   └── PasswordResetTokenDetail.php         - Password reset model
  ├── Mail/
  │   ├── VerifyEmailMail.php                 - Email verification mailable
  │   └── ResetPasswordMail.php                - Password reset mailable
  
database/
  ├── migrations/
  │   ├── 0001_01_01_000003_add_email_verification_tokens.php
  │   └── 0001_01_01_000004_create_password_reset_token_details.php

resources/views/
  ├── auth/
  │   ├── register.blade.php                  - Registration form
  │   ├── login.blade.php                     - Login form
  │   ├── forgot-password.blade.php           - Forgot password form
  │   └── reset-password.blade.php            - Reset password form
  ├── emails/
  │   ├── verify-email.blade.php              - Verification email template
  │   └── reset-password.blade.php            - Reset password email template
  └── dashboard.blade.php                      - Updated with logout button
  
routes/
  └── web.php                                 - Updated with auth routes
```

## Security Notes

1. **Password Hashing:** All passwords are hashed using bcrypt
2. **Token Generation:** Verification and reset tokens are generated using `random_bytes(32)` for security
3. **Token Expiration:** Password reset tokens expire after 10 minutes
4. **CSRF Protection:** All forms are protected with CSRF tokens
5. **Email Verification:** Users cannot login without email verification
6. **Session Management:** Laravel's secure session management is used

## Troubleshooting

### Emails Not Sending
- Check your mail configuration in `.env`
- If using 'log' mode, check `storage/logs/laravel.log`
- Ensure MAIL_FROM_ADDRESS is set

### Can't Login After Verification
- Check that `is_verified` column exists in users table
- Run migrations: `php artisan migrate`

### Reset Link Expired
- Reset links are only valid for 10 minutes
- Users can request a new reset link

### Database Errors
- Ensure all migrations have run: `php artisan migrate`
- Check database connection in `.env`

## Next Steps

1. Run migrations to create database tables
2. Configure email service in `.env`
3. Start your development server
4. Visit `/register` to create an account
5. Complete email verification
6. Login to access the dashboard
