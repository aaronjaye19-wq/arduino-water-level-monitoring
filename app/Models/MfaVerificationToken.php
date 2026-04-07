<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MfaVerificationToken extends Model
{
    protected $table = 'mfa_verification_tokens';

    protected $fillable = [
        'user_id',
        'code',
        'attempts',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if token is expired
     */
    public function isExpired()
    {
        return now()->isAfter($this->expires_at);
    }

    /**
     * Check if token has exceeded max attempts (3 attempts)
     */
    public function hasExceededAttempts()
    {
        return $this->attempts >= 3;
    }

    /**
     * Increment attempt counter
     */
    public function incrementAttempt()
    {
        $this->increment('attempts');
    }
}
