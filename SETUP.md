# Setup Instructions

## Quick Start

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 3. Database Setup

Create a SQLite database (or configure another database in `.env`):

```bash
touch database/database.sqlite
```

Or use MySQL (update `.env` with your database credentials):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=water_monitoring
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations

```bash
php artisan migrate
```

This will create all necessary tables including:
- `users` table with MFA and role columns
- `email_verifications` table for email verification
- `password_reset_tokens` table for password recovery
- `cache` and `jobs` tables

### 5. Create Admin User (Optional)

Use Artisan tinker to create an admin user:

```bash
php artisan tinker
```

Then run:

```php
$user = User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);
exit
```

### 6. Start Development Server

Run the Laravel development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Running the Full Stack

To run Laravel server, queue worker, and logs in parallel:

```bash
composer run dev
```

## Testing the Authentication Flow

### 1. Register a New User

1. Visit `http://localhost:8000/auth/register`
2. Fill in the registration form
3. After registration, you'll see the email verification notice
4. Check logs for the verification link:
   ```bash
   tail -f storage/logs/laravel.log | grep "verification"
   ```
5. Copy the verification link and visit it in your browser

### 2. Login and MFA

1. Visit `http://localhost:8000/auth/login`
2. Enter your email and password
3. You'll be redirected to MFA verification
4. Check logs for your MFA code:
   ```bash
   tail -f storage/logs/laravel.log | grep "MFA Code"
   ```
5. Enter the 6-digit code
6. You'll be logged in and redirected to your dashboard

### 3. Password Recovery

1. Visit `http://localhost:8000/auth/forgot-password`
2. Enter your email
3. Check logs for the password reset link:
   ```bash
   tail -f storage/logs/laravel.log | grep "Password reset"
   ```
4. Visit the link and enter your new password
5. You can now login with the new password

## Environment Variables

Key variables to configure in `.env`:

```env
APP_NAME="Water Level Monitoring"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
# or for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=water_monitoring
# DB_USERNAME=root
# DB_PASSWORD=

# Mail (for sending real emails)
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Water Level Monitoring"
```

## Troubleshooting

### 1. Database Not Found

If you get "database not found" error:

```bash
touch database/database.sqlite
php artisan migrate
```

### 2. Permission Issues

If you get permission denied errors:

```bash
chmod -R 775 storage bootstrap/cache
```

### 3. Migrations Not Running

Force migrations (development only):

```bash
php artisan migrate --force
```

### 4. Clear Cache

If experiencing caching issues:

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 5. Session Issues

If sessions aren't working, regenerate key:

```bash
php artisan key:generate
```

## Sending Real Emails

To send real emails instead of logging to console:

### 1. Configure Mail Provider

Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Water Level Monitoring"
```

### 2. Create Mail Classes

```bash
php artisan make:mail SendMfaCode
php artisan make:mail SendPasswordResetLink
php artisan make:mail SendEmailVerificationLink
```

### 3. Update AuthController

Uncomment the Mail::send() calls and implement the email views.

## Database Schema

### Users Table
```sql
- id (Primary Key)
- name
- email (Unique)
- email_verified_at (Nullable)
- password
- role (admin or user)
- mfa_code (Nullable)
- mfa_code_expires_at (Nullable)
- mfa_verified (Boolean, default: false)
- remember_token
- created_at
- updated_at
```

### Email Verifications Table
```sql
- id (Primary Key)
- user_id (Foreign Key)
- token
- expires_at
- created_at
```

### Password Reset Tokens Table
```sql
- email (Primary Key)
- token
- created_at
- expires_at (60 second expiration)
```

## API Endpoints (For Arduino/Sensor)

### POST /api/sensor
Send sensor data from Arduino:

```bash
curl -X POST http://localhost:8000/api/sensor \
  -H "Content-Type: application/json" \
  -d '{
    "sensor": 75,
    "green": 30,
    "yellow": 60,
    "red": 90
  }'
```

### GET /api/latest-sensor
Get latest sensor reading:

```bash
curl http://localhost:8000/api/latest-sensor
```

Response:
```json
{
  "sensor": 75,
  "green": 30,
  "yellow": 60,
  "red": 90
}
```

## Next Steps

1. Test the complete authentication flow
2. Implement email sending (see AUTHENTICATION.md)
3. Create admin user in database
4. Customize dashboards as needed
5. Connect your Arduino sensor to `/api/sensor` endpoint
6. Deploy to production server

## Support

For issues or questions, refer to:
- `AUTHENTICATION.md` - Detailed feature documentation
- `routes/web.php` - All available routes
- `app/Http/Controllers/AuthController.php` - Authentication logic
