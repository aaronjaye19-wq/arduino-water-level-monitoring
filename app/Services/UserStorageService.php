<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserStorageService
{
    private $usersFile;
    private $defaultUsersFile;

    public function __construct()
    {
        $this->usersFile = storage_path('app/users/users.json');
        $this->defaultUsersFile = base_path('database/defaults/default-users.json');
        $this->ensureStorageExists();
    }

    private function ensureStorageExists()
    {
        // Ensure directory exists
        if (!is_dir(dirname($this->usersFile))) {
            mkdir(dirname($this->usersFile), 0755, true);
        }

        // Initialize with default users if empty
        if (!file_exists($this->usersFile)) {
            $this->initializeDefaultUsers();
        }
    }

    private function initializeDefaultUsers()
    {
        $defaultUsers = [
            [
                'id' => '1',
                'name' => 'Admin User',
                'email' => 'admin@waterflow.local',
                'password' => Hash::make('Admin@123456'),
                'role' => 'admin',
                'email_verified' => true,
                'email_verified_at' => now()->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ]
        ];

        file_put_contents($this->usersFile, json_encode($defaultUsers, JSON_PRETTY_PRINT));
    }

    public function all()
    {
        $this->ensureStorageExists();
        $content = file_get_contents($this->usersFile);
        return json_decode($content, true) ?? [];
    }

    public function findByEmail($email)
    {
        $users = $this->all();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    public function findById($id)
    {
        $users = $this->all();
        foreach ($users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    public function create($data)
    {
        $users = $this->all();
        $id = (string)(count($users) + 1);

        $newUser = [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
            'email_verified' => false,
            'email_verified_at' => null,
            'created_at' => now()->toDateTimeString(),
        ];

        $users[] = $newUser;
        file_put_contents($this->usersFile, json_encode($users, JSON_PRETTY_PRINT));

        return $newUser;
    }

    public function update($email, $data)
    {
        $users = $this->all();

        foreach ($users as $key => $user) {
            if ($user['email'] === $email) {
                $users[$key] = array_merge($user, $data);
                file_put_contents($this->usersFile, json_encode($users, JSON_PRETTY_PRINT));
                return $users[$key];
            }
        }

        return null;
    }

    public function verifyEmail($email)
    {
        return $this->update($email, [
            'email_verified' => true,
            'email_verified_at' => now()->toDateTimeString(),
        ]);
    }

    public function updatePassword($email, $newPassword)
    {
        return $this->update($email, [
            'password' => Hash::make($newPassword),
        ]);
    }
}
