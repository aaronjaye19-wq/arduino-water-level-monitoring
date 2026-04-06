# Database Migration Guide

## Quick Migration Steps

### Step 1: Ensure Database Connection
Make sure your `.env` file has correct database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=your_password
```

Or for SQLite:
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### Step 2: Run All Migrations

Run this command to migrate all pending migrations (including the two new authentication tables):

```bash
php artisan migrate
```

This will create the following new tables:
- `email_verification_tokens` - Stores email verification tokens for new users
- `password_reset_token_details` - Stores password reset tokens with expiration times

### Step 3: Verify Migration Success

You should see output like:
```
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table (123.45ms)
Migrating: 0001_01_01_000001_create_cache_table
Migrated:  0001_01_01_000001_create_cache_table (456.78ms)
Migrating: 0001_01_01_000002_create_jobs_table
Migrated:  0001_01_01_000002_create_jobs_table (789.01ms)
Migrating: 0001_01_01_000003_add_email_verification_tokens
Migrated:  0001_01_01_000003_add_email_verification_tokens (234.56ms)
Migrating: 0001_01_01_000004_create_password_reset_token_details
Migrated:  0001_01_01_000004_create_password_reset_token_details (345.67ms)
```

---

## Advanced Migration Commands

### Run Fresh Migration (Reset Database)
⚠️ **WARNING: This will delete all existing data!**

```bash
php artisan migrate:fresh
```

This drops all tables and re-runs all migrations from scratch.

### See Migration Status
Check which migrations have been run:

```bash
php artisan migrate:status
```

### Rollback Last Migration Batch
Undo the last set of migrations:

```bash
php artisan migrate:rollback
```

### Rollback All Migrations
Undo all migrations (drops all tables):

```bash
php artisan migrate:rollback --step=5
```

### Rollback Specific Migration
Rollback only the authentication migrations:

```bash
php artisan migrate:rollback --step=2
```

---

## Database Schema Overview

### email_verification_tokens Table
```
- id (Primary Key)
- user_id (Foreign Key → users)
- token (Unique, 64 characters)
- created_at (Timestamp)
```

**Purpose:** Stores unique tokens sent to new users for email verification. Each token is valid for one verification.

### password_reset_token_details Table
```
- id (Primary Key)
- user_id (Foreign Key → users)
- token (Unique, 64 characters)
- expires_at (Timestamp - 10 minutes from creation)
- created_at (Timestamp)
- updated_at (Timestamp)
```

**Purpose:** Stores password reset tokens with expiration. Tokens are automatically considered expired after 10 minutes.

---

## Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1030"
**Solution:** Your database might be full or permissions issue. Check:
```bash
php artisan migrate --step=1
```

### Error: "Call to undefined method create()"
**Solution:** Make sure you're running Laravel 10+. Check:
```bash
php artisan --version
```

### Error: "No such table: migrations"
**Solution:** The migrations table wasn't created. Run:
```bash
php artisan migrate:install
php artisan migrate
```

### Migrations Won't Run
**Solution:** Check database connection:
```bash
php artisan tinker
>>> DB::connection()->getPdo()
```

If it throws an error, your database credentials in `.env` are incorrect.

---

## What Gets Created

After running `php artisan migrate`, you'll have these tables:

1. **users** - User accounts (existing)
2. **cache** - Cache storage (existing)
3. **jobs** - Job queue (existing)
4. **email_verification_tokens** - ✅ NEW
5. **password_reset_token_details** - ✅ NEW

The `users` table is automatically updated with:
- `is_verified` column (boolean, default: false)
- Existing columns: id, name, email, password, remember_token, created_at, updated_at

---

## Next Steps

After migrations complete:

1. Test registration: `http://localhost:8000/register`
2. Test login: `http://localhost:8000/login`
3. Test password reset: `http://localhost:8000/forgot-password`

See `QUICKSTART.md` for complete setup instructions.
