# Quick Start Guide - Authentication System

Get the authentication system running in 5 minutes!

## Step 1: Run Database Migrations

Execute the migrations to create the necessary database tables:

```bash
php artisan migrate
```

This will create:
- `email_verification_tokens` table
- `password_reset_token_details` table
- Add `is_verified` column to `users` table

## Step 2: Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Step 3: Test Registration

1. Navigate to `http://localhost:8000/register`
2. Fill in the registration form:
   - Full Name: Any name
   - Email: Any email address
   - Password: At least 8 characters
   - Confirm Password: Same as above
3. Click "Register"

## Step 4: Verify Email

Since email is in log mode by default:

1. Check the logs in `storage/logs/laravel.log`
2. Find the email content with the verification link
3. Copy the verification URL (looks like: `/email/verify/xxxxx...`)
4. Paste in your browser address bar

Or manually test:
```bash
php artisan tinker
# Then:
$user = App\Models\User::first();
$token = $user->emailVerificationTokens->first()->token;
# Visit: http://localhost:8000/email/verify/{$token}
```

## Step 5: Login

1. Navigate to `http://localhost:8000/login`
2. Enter your email and password
3. Click "Login"
4. You'll be redirected to the dashboard

## Step 6: Test Password Reset

1. Go to `/login` page
2. Click "Forgot Password?"
3. Enter your email
4. Check `storage/logs/laravel.log` for the reset link
5. Copy and visit the reset link (valid for 10 minutes)
6. Enter new password and confirm
7. Click "Reset Password"
8. Login with new password

## Step 7: Logout

Click the "Logout" button on the dashboard to logout.

---

## Common Commands

```bash
# Run all migrations
php artisan migrate

# Rollback migrations (if needed)
php artisan migrate:rollback

# Refresh database (clears all data)
php artisan migrate:refresh

# Check app status
php artisan tinker
>>> User::all()

# View logs
tail -f storage/logs/laravel.log

# Run artisan command
php artisan [command]
```

---

## Email Testing in Log Mode

The default mail driver logs emails to `storage/logs/laravel.log`

To monitor emails in real-time:
```bash
tail -f storage/logs/laravel.log
```

Look for entries with:
- "Verification email sent" → Contains verification link
- "Password reset link sent" → Contains reset link

---

## Routes Overview

| Route | Method | Purpose |
|-------|--------|---------|
| `/register` | GET | Registration form |
| `/register` | POST | Submit registration |
| `/login` | GET | Login form |
| `/login` | POST | Submit login |
| `/email/verify/{token}` | GET | Verify email |
| `/forgot-password` | GET | Forgot password form |
| `/forgot-password` | POST | Send reset email |
| `/reset-password/{token}` | GET | Reset password form |
| `/reset-password/{token}` | POST | Submit new password |
| `/dashboard` | GET | Dashboard (protected) |
| `/logout` | POST | Logout |

---

## Database Credentials

Edit `.env` if you need to change database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

## Email Configuration (Optional)

The system works with logs by default. To send real emails, update `.env`:

### Gmail Example:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Water Sensor Dashboard"
```

Then restart the server.

---

## Troubleshooting

**"Migration table not found"**
- Run: `php artisan migrate:install` first, then `php artisan migrate`

**"Can't login after verification"**
- Make sure you visited the email verification link
- Check `is_verified` column is true: `User::first()->is_verified`

**"Reset link expired"**
- Reset links expire after 10 minutes
- Request a new one at `/forgot-password`

**"Emails not showing in logs"**
- Check file exists: `storage/logs/laravel.log`
- Ensure Laravel can write to storage: `chmod 777 storage -R`

**"Port 8000 already in use"**
- Use different port: `php artisan serve --port=8001`

---

## Features Implemented

✅ User Registration  
✅ Email Verification (Required before login)  
✅ Login System  
✅ Password Recovery (10-minute token expiration)  
✅ Dashboard Protection (Auth required)  
✅ Logout  
✅ CSRF Protection  
✅ Password Hashing (Bcrypt)  
✅ Secure Token Generation  
✅ Email Templates  

---

## What's Next?

1. **Customize the design** - Update CSS in the view files
2. **Add more features** - User profiles, settings, etc.
3. **Deploy to production** - Configure real email service
4. **Monitor logs** - Set up log aggregation for production
5. **Setup backups** - Configure database backups

---

## Need Help?

Check these files for detailed information:
- **Full Setup:** `AUTH_SETUP.md`
- **Email Config:** `ENV_AUTH_CONFIG.md`
- **Implementation Details:** `IMPLEMENTATION_SUMMARY.md`

---

## Done! 🎉

You now have a fully functional authentication system with email verification and password recovery!
