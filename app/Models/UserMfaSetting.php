<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMfaSetting extends Model
{
    protected $table = 'user_mfa_settings';

    protected $fillable = [
        'user_id',
        'secret',
        'backup_codes',
        'is_enabled',
    ];

    protected $casts = [
        'backup_codes' => 'array',
        'is_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a 6-digit TOTP code using the secret
     */
    public function generateTotpCode()
    {
        // Using a simple time-based code generation
        $secret = $this->secret;
        $time = floor(time() / 30); // 30-second time step
        
        // Simple HMAC-based calculation (basic TOTP)
        $hmac = hash_hmac('sha1', pack('N', $time), base64_decode($secret), true);
        $offset = ord($hmac[19]) & 0xf;
        $code = (unpack('N', substr($hmac, $offset, 4))[1] & 0x7fffffff) % 1000000;
        
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify if a provided code matches current TOTP code
     */
    public function verifyCode($code)
    {
        $currentCode = $this->generateTotpCode();
        return $code === $currentCode;
    }
}
