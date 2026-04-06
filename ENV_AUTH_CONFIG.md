# Email Configuration for Authentication System

The authentication system sends emails for:
1. Email verification links for new users
2. Password reset links for users who forgot their password

## Default Configuration (Log Mode)

By default, the app uses 'log' mode which stores emails in logs instead of sending them. This is perfect for development.

Check the logs in: `storage/logs/laravel.log`

To use log mode, your `.env` should have:
```
MAIL_MAILER=log
```

## Production Configuration Options

### Option 1: Gmail SMTP

1. Enable "Less secure app access" on your Gmail account or use an App Password
2. Update your `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password-or-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Water Sensor Dashboard"
```

### Option 2: SendGrid

1. Create a SendGrid account and get your API key
2. Update your `.env`:

```
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Water Sensor Dashboard"
```

### Option 3: Mailgun

1. Create a Mailgun account
2. Update your `.env`:

```
MAIL_MAILER=mailgun
MAILGUN_SECRET=key-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MAILGUN_DOMAIN=sandboxc1234567890abcdefghij.mailgun.org
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Water Sensor Dashboard"
```

### Option 4: AWS SES

1. Set up AWS SES credentials
2. Update your `.env`:

```
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Water Sensor Dashboard"
```

### Option 5: Custom SMTP Server

```
MAIL_MAILER=smtp
MAIL_HOST=your-mail-server.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Water Sensor Dashboard"
```

## Email Templates

The system sends two types of emails:

### 1. Email Verification
- Sent when user registers
- Contains a link to verify email
- Template: `resources/views/emails/verify-email.blade.php`

### 2. Password Reset
- Sent when user requests password reset
- Contains a link valid for 10 minutes
- Template: `resources/views/emails/reset-password.blade.php`

## Testing Email Configuration

To verify your email configuration works:

1. Create a test file or use Tinker:
   ```bash
   php artisan tinker
   ```

2. Send a test email:
   ```php
   Mail::raw('Test email', function ($message) {
       $message->to('your-test-email@example.com')
               ->subject('Test Email');
   });
   ```

3. Check if the email was sent (or logged if using log mode)

## Troubleshooting Email Issues

### Emails not being sent

1. Check MAIL_MAILER is set correctly
2. Verify credentials (username, password, API keys)
3. Check firewall/network settings if using SMTP
4. Review logs: `storage/logs/laravel.log`

### SMTP Connection Errors

- Ensure correct MAIL_HOST and MAIL_PORT
- Check MAIL_ENCRYPTION setting (tls or null)
- Verify firewall allows outgoing connections on the specified port

### API Key Errors

- Verify API key is correct and active
- Check for extra spaces in API_KEY value
- Ensure account has email sending permissions

### Common Ports

- SMTP: 587 (TLS) or 465 (SSL)
- SendGrid API: HTTPS
- Mailgun API: HTTPS

## Monitoring Email Status

For production, consider logging email sends to the database. You can extend the `AuthController` to log email events:

```php
// Log email sends
Log::info('Verification email sent', ['user_id' => $user->id, 'email' => $user->email]);
```

## Security Best Practices

1. Never commit `.env` file to version control
2. Use environment variables for all sensitive credentials
3. Rotate API keys periodically
4. Use app-specific passwords (not main account passwords)
5. Enable email delivery notifications for monitoring
6. Set up bounce/complaint handling for transactional emails
