# Gmail SMTP Setup Guide for Email Verification

This guide will walk you through configuring Gmail SMTP for sending email verification and password reset emails.

## Step 1: Get Your Gmail App Password

### If you have 2-Factor Authentication enabled (Recommended):

1. Go to your Google Account: https://myaccount.google.com/
2. Click on **Security** in the left sidebar
3. Scroll down to **How you sign in to Google**
4. Enable **2-Step Verification** if not already enabled
5. Go back to Security and scroll down to **App passwords**
6. Select "Mail" and "Windows Computer" (or your device)
7. Google will generate a **16-character app password**
8. **Copy this password** - you'll need it for .env

### If you DON'T have 2-Factor Authentication:

1. You need to enable "Less secure app access" (not recommended for production):
2. Go to: https://myaccount.google.com/lesssecureapps
3. Toggle **Allow less secure apps** to ON
4. Use your regular Gmail password in the .env file

## Step 2: Update Your .env File

Open `.env` in your project root and configure the mail settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password-or-gmail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Example:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=myproject@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=myproject@gmail.com
MAIL_FROM_NAME="Water Level Monitoring"
```

## Step 3: Test Your Configuration

Run this command to test if emails are sending properly:

```bash
php artisan tinker
```

Then in the Tinker shell:

```php
Mail::raw('Test email body', function($message) {
    $message->to('your-test-email@gmail.com')
            ->subject('Test Email');
});
```

You should receive a test email within seconds.

## Step 4: Common Issues & Fixes

### Issue: "SMTP Error: Could not connect to SMTP host"

**Solution:**
- Check MAIL_HOST is `smtp.gmail.com`
- Check MAIL_PORT is `587` (not 465 for TLS)
- Ensure your Gmail account credentials are correct

### Issue: "535 5.7.8 Username and password not accepted"

**Causes & Solutions:**
1. Using regular Gmail password instead of App Password (if 2FA enabled)
   - Generate an App Password from https://myaccount.google.com/apppasswords
   
2. Using wrong email format
   - Make sure MAIL_USERNAME is full email: `email@gmail.com`
   
3. App password has spaces
   - Remove any spaces in MAIL_PASSWORD
   
4. Account security blocking login
   - Check your Gmail account for "Security alert"
   - Allow less secure apps: https://myaccount.google.com/lesssecureapps

### Issue: "Emails not being sent but no errors"

**Solution:**
- Check Laravel logs: `tail -f storage/logs/laravel.log`
- Make sure .env changes are loaded: `php artisan config:cache && php artisan config:clear`
- Restart your Laravel server

## Step 5: Verify It Works

1. Go to `/register` in your app
2. Register a new account with your test email
3. Check your email inbox for verification link
4. Click the verification link
5. You should be redirected to login

## Important Notes

- **Gmail has a sending limit**: 500 emails per day for regular Gmail accounts
- **Don't commit .env to Git**: Make sure .env is in .gitignore
- **Use App Passwords in production**: Never use "Less secure apps" for production
- **Keep app password safe**: Treat it like a password

## For Production Deployment (Heroku, AWS, etc.)

Set environment variables on your hosting platform:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Water Level Monitoring"
```

## Alternative: Use Gmail API (Advanced)

If you want more control, you can use Google's Mail API instead of SMTP. This requires additional package installation and is more complex. Contact support if you need this.

---

**That's it!** Your email verification and password reset should now work through Gmail.
