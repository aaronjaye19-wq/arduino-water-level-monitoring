# Arduino Water Level Monitoring System - Setup Guide

## Overview
This Laravel application provides a complete authentication system with email verification and password reset capabilities, along with an admin dashboard for monitoring water levels from Arduino sensors.

## Features Implemented

### 1. Authentication System
- **User Registration**: New users can create accounts with name, email, and password
- **Email OTP Verification**: 6-digit one-time password sent via Gmail for email verification
- **Login**: Users can log in after email verification
- **Forgot Password**: Users can request password reset links via email
- **Password Reset**: Time-limited (1 hour) token-based password reset
- **Role-Based System**: Support for 'admin' and 'user' roles

### 2. Email System
- **Gmail SMTP Configuration**: Uses Gmail's SMTP server for sending emails
- **OTP Emails**: Beautiful HTML emails with 6-digit verification codes (15-minute expiry)
- **Password Reset Emails**: HTML emails with time-limited reset links (1-hour expiry)

### 3. Admin Dashboard
- **Real-time Water Level Display**: Visual tank representation with animated water
- **Status Indicators**: Green (normal), Yellow (warning), Red (critical) lights
- **Statistics Cards**: Current, max, min, and average water levels
- **Recent Readings**: Live updates from sensor
- **Readings History Table**: Last 24 hours of measurements
- **Modern CSS UI**: Professional, responsive design with gradients and animations

### 4. Security
- **CSRF Protection**: Token-based CSRF protection on all forms
- **Password Hashing**: bcrypt hashing for passwords
- **Rate Limiting Ready**: OTP attempts limited to 3 per request
- **Token Expiration**: OTP (15 min) and Reset links (60 min) expire automatically

## Installation & Setup

### Prerequisites
- PHP 8.1+
- Composer
- SQLite or MySQL database
- Gmail Account (for SMTP)

### Step 1: Install Dependencies
```bash
composer install
```

### Step 2: Configure Environment Variables

Edit `.env` file with your Gmail credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Water Level Monitoring"

# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

#### Getting Gmail App Password
1. Go to https://myaccount.google.com
2. Enable 2-Factor Authentication
3. Go to App Passwords (create specific password for Laravel)
4. Copy the generated password and paste in MAIL_PASSWORD

### Step 3: Generate Application Key
```bash
php artisan key:generate
```

### Step 4: Run Database Migrations
```bash
php artisan migrate
```

### Step 5: Create Database (SQLite)
If using SQLite:
```bash
touch database/database.sqlite
```

### Step 6: Start Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## User Flow

### Registration & Verification
1. User visits `/register` and fills in name, email, password
2. System creates user account with `is_verified = false`
3. 6-digit OTP is generated and sent to user's email
4. User navigates to `/verify-otp` and enters the code
5. Upon successful verification, user is marked as verified and logged in
6. User is redirected to `/dashboard`

### Login
1. User visits `/login` and enters email & password
2. System checks if email is verified
3. If not verified, user is redirected to OTP verification page
4. If verified, user is logged in and redirected to dashboard

### Forgot Password
1. User visits `/forgot-password` and enters email
2. System generates a reset token and sends via email
3. User clicks reset link in email, goes to `/password/reset/{token}`
4. User enters new password and confirms
5. Password is updated and user can log in with new password

## Database Schema

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email address
- `password` - Hashed password
- `role` - 'admin' or 'user'
- `is_verified` - Email verification status
- `email_verified_at` - Timestamp of verification
- `timestamps` - Created and updated timestamps

### OTP Verifications Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `email` - Email address
- `otp_code` - 6-digit code
- `expires_at` - Expiration timestamp
- `attempts` - Number of failed attempts
- `timestamps` - Created and updated timestamps

### Password Reset Tokens Table
- `email` - User's email
- `token` - Hashed reset token
- `created_at` - Token creation time

### Water Level Readings Table
- `id` - Primary key
- `sensor_id` - Sensor identifier
- `water_level` - Level in centimeters
- `temperature` - Optional temperature reading
- `humidity` - Optional humidity reading
- `recorded_at` - Timestamp of reading
- `timestamps` - Created and updated timestamps

## API Endpoints

### Arduino Sensor Endpoint (No CSRF Required)
```
POST /api/sensor
Body: {
    "sensor": 150,
    "green": 1,
    "yellow": 0,
    "red": 0
}
```

### Get Latest Sensor Data
```
GET /api/latest-sensor
Response: {
    "sensor": 150,
    "green": 1,
    "yellow": 0,
    "red": 0
}
```

## Routes Overview

### Public Routes
- `GET /login` - Login page
- `POST /login` - Login submission
- `GET /register` - Registration page
- `POST /register` - Registration submission
- `GET /verify-otp` - OTP verification page
- `POST /verify-otp` - OTP verification submission
- `POST /resend-otp` - Resend OTP
- `GET /forgot-password` - Forgot password page
- `POST /send-reset-link` - Send reset link
- `GET /password/reset/{token}` - Reset password page
- `POST /password/reset` - Reset password submission

### Protected Routes
- `GET /dashboard` - Admin dashboard (requires authentication)
- `POST /logout` - User logout

### API Routes
- `POST /api/sensor` - Receive sensor data (no CSRF)
- `GET /api/latest-sensor` - Get latest reading

## Troubleshooting

### Email Not Sending
1. Check `.env` file has correct Gmail credentials
2. Verify 2FA is enabled on Gmail account
3. Verify app-specific password was generated correctly
4. Check Laravel logs: `storage/logs/laravel.log`

### OTP Not Working
1. Check OTP hasn't expired (15 minutes)
2. Verify email was received
3. Check for typos in OTP code
4. Maximum 3 attempts allowed

### Login Issues
1. Verify email is verified first
2. Check password is correct
3. Ensure user exists in database
4. Check session is enabled in config

## File Structure

```
app/
├── Http/Controllers/Auth/
│   ├── LoginController.php
│   ├── RegisterController.php
│   ├── OtpVerificationController.php
│   ├── ForgotPasswordController.php
│   └── ResetPasswordController.php
├── Mail/
│   ├── SendOtpCode.php
│   └── SendPasswordResetToken.php
└── Models/
    ├── User.php
    ├── OtpVerification.php
    └── WaterLevelReading.php

database/migrations/
├── 2014_10_12_000000_create_users_table.php
├── 2025_04_01_000001_create_otp_verifications_table.php
├── 2025_04_01_000002_create_password_reset_tokens_table.php
└── 2025_04_01_000003_create_water_level_readings_table.php

resources/views/
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── verify-otp.blade.php
│   ├── forgot-password.blade.php
│   └── reset-password.blade.php
├── emails/
│   ├── otp-code.blade.php
│   └── password-reset.blade.php
└── dashboard.blade.php

routes/
└── web.php
```

## Security Recommendations

1. **Change the APP_KEY** - Already generated during setup
2. **Enable HTTPS** - Use HTTPS in production
3. **Add Rate Limiting** - Consider adding rate limiting middleware
4. **Email Verification** - OTP expires after 15 minutes
5. **Password Reset** - Reset links expire after 60 minutes
6. **CORS Configuration** - Configure for your domain if needed

## Development Tips

### Testing Email Locally
Use Mailtrap or similar service:
```env
MAIL_MAILER=mailtrap
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

### Debugging
Enable debug mode in `.env`:
```env
APP_DEBUG=true
```

### Running Migrations
```bash
php artisan migrate:fresh  # Reset and run all migrations
php artisan migrate:rollback  # Rollback last migration
```

## Support
For issues or questions, please check the Laravel documentation at https://laravel.com/docs

## License
This project is provided as-is for educational and commercial use.
