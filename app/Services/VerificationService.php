<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;

class VerificationService
{
    private $codesFile;

    public function __construct()
    {
        $this->codesFile = storage_path('app/users/verification_codes.json');
        $this->ensureFileExists();
    }

    private function ensureFileExists()
    {
        if (!file_exists(dirname($this->codesFile))) {
            mkdir(dirname($this->codesFile), 0755, true);
        }

        if (!file_exists($this->codesFile)) {
            file_put_contents($this->codesFile, json_encode([]));
        }
    }

    public function generateCode($email)
    {
        // Generate a 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $codes = $this->getAllCodes();
        
        // Remove old code if exists
        $codes = array_filter($codes, function($item) use ($email) {
            return $item['email'] !== $email;
        });
        
        // Add new code with expiration (15 minutes)
        $codes[] = [
            'email' => $email,
            'code' => $code,
            'created_at' => now()->toDateTimeString(),
            'expires_at' => now()->addMinutes(15)->toDateTimeString(),
        ];
        
        file_put_contents($this->codesFile, json_encode($codes, JSON_PRETTY_PRINT));
        
        return $code;
    }

    public function verifyCode($email, $code)
    {
        $codes = $this->getAllCodes();

        foreach ($codes as $key => $item) {
            if ($item['email'] === $email && $item['code'] === $code) {
                // Check if code is not expired
                if (Carbon::parse($item['expires_at'])->isFuture()) {
                    // Remove used code
                    unset($codes[$key]);
                    file_put_contents($this->codesFile, json_encode(array_values($codes), JSON_PRETTY_PRINT));
                    return true;
                }
            }
        }

        return false;
    }

    public function getAllCodes()
    {
        $this->ensureFileExists();
        $content = file_get_contents($this->codesFile);
        return json_decode($content, true) ?? [];
    }

    public function getCodeForEmail($email)
    {
        $codes = $this->getAllCodes();

        foreach ($codes as $item) {
            if ($item['email'] === $email && Carbon::parse($item['expires_at'])->isFuture()) {
                return $item['code'];
            }
        }

        return null;
    }

    public function cleanupExpiredCodes()
    {
        $codes = $this->getAllCodes();
        
        $codes = array_filter($codes, function($item) {
            return Carbon::parse($item['expires_at'])->isFuture();
        });
        
        file_put_contents($this->codesFile, json_encode(array_values($codes), JSON_PRETTY_PRINT));
    }
}
