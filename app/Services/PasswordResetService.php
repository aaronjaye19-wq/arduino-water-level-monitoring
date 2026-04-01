<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetService
{
    private $resetFile;

    public function __construct()
    {
        $this->resetFile = storage_path('app/users/password_resets.json');
        $this->ensureFileExists();
    }

    private function ensureFileExists()
    {
        if (!file_exists(dirname($this->resetFile))) {
            mkdir(dirname($this->resetFile), 0755, true);
        }

        if (!file_exists($this->resetFile)) {
            file_put_contents($this->resetFile, json_encode([]));
        }
    }

    public function generateToken($email)
    {
        // Generate a unique token
        $token = Str::random(64);
        
        $resets = $this->getAllResets();
        
        // Remove old token if exists
        $resets = array_filter($resets, function($item) use ($email) {
            return $item['email'] !== $email;
        });
        
        // Add new token with expiration (1 hour)
        $resets[] = [
            'email' => $email,
            'token' => $token,
            'created_at' => now()->toDateTimeString(),
            'expires_at' => now()->addHour()->toDateTimeString(),
        ];
        
        file_put_contents($this->resetFile, json_encode($resets, JSON_PRETTY_PRINT));
        
        return $token;
    }

    public function verifyToken($token)
    {
        $resets = $this->getAllResets();

        foreach ($resets as $item) {
            if ($item['token'] === $token) {
                // Check if token is not expired
                if (Carbon::parse($item['expires_at'])->isFuture()) {
                    return $item['email'];
                }
            }
        }

        return null;
    }

    public function consumeToken($token)
    {
        $resets = $this->getAllResets();
        
        $resets = array_filter($resets, function($item) use ($token) {
            return $item['token'] !== $token;
        });
        
        file_put_contents($this->resetFile, json_encode(array_values($resets), JSON_PRETTY_PRINT));
    }

    public function getAllResets()
    {
        $this->ensureFileExists();
        $content = file_get_contents($this->resetFile);
        return json_decode($content, true) ?? [];
    }

    public function cleanupExpiredTokens()
    {
        $resets = $this->getAllResets();
        
        $resets = array_filter($resets, function($item) {
            return Carbon::parse($item['expires_at'])->isFuture();
        });
        
        file_put_contents($this->resetFile, json_encode(array_values($resets), JSON_PRETTY_PRINT));
    }
}
