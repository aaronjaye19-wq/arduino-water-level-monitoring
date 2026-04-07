# Deployment Checklist - MFA & Admin System

## Pre-Deployment

### Database Setup
- [ ] Ensure database connection works (`php artisan migrate:status`)
- [ ] Backup existing database (if applicable)
- [ ] Clear any migration history issues

### Dependencies
- [ ] Laravel 11+ installed
- [ ] PHP 8.2+ installed
- [ ] composer dependencies up to date

## Deployment Steps

### Step 1: Run Migrations
```bash
php artisan migrate
```

**What it does:**
- Creates `user_mfa_settings` table
- Creates `mfa_verification_tokens` table
- Adds `is_admin` and `mfa_enabled` columns to `users` table

**Verify:**
```bash
php artisan migrate:status
# All migrations should show "Yes" in Batch column
```

### Step 2: Clear Cache
```bash
php artisan config:cache
php artisan view:cache
```

**Why:** Routes and views are cached

### Step 3: Create First Admin User
```sql
-- In your database
UPDATE users SET is_admin = true 
WHERE email = 'your-admin-email@example.com';
```

**Verify:**
```sql
SELECT id, name, email, is_admin FROM users;
```

### Step 4: Test Routes
```bash
php artisan route:list | grep admin
php artisan route:list | grep mfa
```

**You should see:**
- `/admin/dashboard` - GET (admin middleware)
- `/admin/users` - GET (admin middleware)
- `/mfa/verify` - GET, POST
- `/mfa/setup` - GET, POST (auth middleware)
- `/mfa/disable` - POST (auth middleware)

### Step 5: Test Middleware
```bash
php artisan tinker

# Check middleware is registered
>>> app('router')->getMiddlewareAliases()
```

**Should show:**
```
'admin' => App\Http\Middleware\CheckAdminRole::class
'mfa' => App\Http\Middleware\CheckMfa::class
```

## Post-Deployment Testing

### Test 1: User Registration & Email Verification
- [ ] Register new user at `/register`
- [ ] Receive verification email
- [ ] Click verification link
- [ ] Email marked as verified in database

```bash
# Check in tinker
>>> User::where('email', 'test@example.com')->first()->is_verified
=> true
```

### Test 2: Login Without MFA
- [ ] Login with email and password
- [ ] Should access `/dashboard` immediately
- [ ] `mfa_enabled` is false for this user

### Test 3: Enable MFA
- [ ] Click "Enable MFA" on dashboard
- [ ] See MFA setup wizard
- [ ] Download Google Authenticator app
- [ ] Scan secret or enter manually
- [ ] Enter 6-digit code
- [ ] MFA should be enabled

```bash
# Check in database
>>> User::find(1)->mfa_enabled
=> true
```

### Test 4: Login With MFA
- [ ] Logout
- [ ] Login with credentials
- [ ] Redirected to `/mfa/verify`
- [ ] Enter 6-digit code from authenticator
- [ ] Should login successfully
- [ ] Session should have `mfa_verified` flag

### Test 5: Admin Dashboard Access
- [ ] Login as admin user
- [ ] Visit `/admin/dashboard`
- [ ] Should see system overview stats
- [ ] Click "Manage Users"
- [ ] Should see user management table

### Test 6: Non-Admin Gets 404
- [ ] Login as regular (non-admin) user
- [ ] Try to access `/admin/dashboard`
- [ ] Should see 404 page (NOT redirect or 403)

```bash
# In terminal, as regular user
curl -H "Cookie: LARAVEL_SESSION=..." http://localhost/admin/dashboard
# Should return 404 status code
```

### Test 7: User Management
- [ ] As admin, visit `/admin/users`
- [ ] See all users in table
- [ ] Test "Grant Admin" button
- [ ] User should become admin
- [ ] Test "Revoke Admin" button
- [ ] User should lose admin status

### Test 8: Admin Can Disable MFA
- [ ] User has MFA enabled
- [ ] As admin, visit `/admin/users`
- [ ] Click "Disable MFA" for user
- [ ] User's `mfa_enabled` should be false
- [ ] User can login without MFA code

### Test 9: Delete User
- [ ] As admin, visit `/admin/users`
- [ ] Click "Delete User" on a test account
- [ ] User should be removed from database
- [ ] Verify in database: `User::find($id)` returns null

## Production Considerations

### Security
- [ ] Ensure HTTPS is enabled (env `APP_URL=https://...`)
- [ ] Verify CSRF tokens are working
- [ ] Check that passwords are hashed (bcrypt)
- [ ] Verify email is being sent correctly for verification

### Performance
- [ ] Monitor database query performance
- [ ] Check for N+1 queries in user listing
- [ ] Verify caching is working (`php artisan config:cache`)
- [ ] Monitor session table (may need cleanup)

### Monitoring
- [ ] Log failed MFA attempts: `/storage/logs/laravel.log`
- [ ] Monitor for brute force attempts
- [ ] Check admin activity logs
- [ ] Monitor failed logins

### Backups
- [ ] Database backups configured
- [ ] Secret keys backed up securely
- [ ] User secrets backed up (for account recovery)

## Troubleshooting

### Migration Fails
```bash
# Check for syntax errors
php artisan make:migration --list

# Rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### Routes Not Found
```bash
# Clear route cache
php artisan route:clear

# Verify routes registered
php artisan route:list | grep mfa
php artisan route:list | grep admin
```

### Middleware Not Working
```bash
# Check middleware aliases in bootstrap/app.php
php artisan tinker
>>> app('router')->getMiddlewareAliases()

# Should show 'admin' and 'mfa'
```

### MFA Code Always Fails
- [ ] Check server time is synchronized
- [ ] Verify TOTP algorithm is correct (HMAC-SHA1, 30s window)
- [ ] Check authenticator app time is correct
- [ ] Clear browser cache

### Admin Can't Access Dashboard
- [ ] Verify `is_admin = true` in database
- [ ] Check user is logged in
- [ ] Check middleware registered
- [ ] Look for errors in `/storage/logs/laravel.log`

## Rollback Plan

If you need to rollback the MFA and Admin system:

```bash
# Revert migrations
php artisan migrate:rollback

# This will:
# - Drop user_mfa_settings table
# - Drop mfa_verification_tokens table
# - Remove is_admin and mfa_enabled columns from users
```

**Note**: Make sure to backup any important data first!

## Monitoring After Deployment

### Daily Checks
- [ ] Check error logs: `tail -f storage/logs/laravel.log`
- [ ] Monitor admin logins
- [ ] Check for failed MFA attempts
- [ ] Verify email delivery is working

### Weekly Checks
- [ ] Review user statistics
- [ ] Check for inactive admin accounts
- [ ] Verify backup integrity
- [ ] Monitor database size

## Support & Documentation

- **Setup Guide**: `MFA_AND_ADMIN_SETUP.md`
- **Implementation Details**: `MFA_ADMIN_IMPLEMENTATION.md`
- **Quick Reference**: This file

---

**Deployment Status**: Ready to deploy after running migrations!
