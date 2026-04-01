<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetupApp extends Command
{
    protected $signature = 'app:setup';
    protected $description = 'Setup the application with database and admin user';

    public function handle()
    {
        $this->info('Starting application setup...');

        // Run migrations
        $this->info('Running migrations...');
        $this->call('migrate', ['--force' => true]);
        $this->info('✓ Migrations completed');

        // Create admin user
        $this->info('Creating admin user...');
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'mfa_verified' => true,
            ]
        );
        $this->info('✓ Admin user created: admin@example.com / password123');

        // Create test user
        $this->info('Creating test user...');
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'mfa_verified' => true,
            ]
        );
        $this->info('✓ Test user created: user@example.com / password123');

        $this->info('✓ Application setup completed successfully!');
    }
}
