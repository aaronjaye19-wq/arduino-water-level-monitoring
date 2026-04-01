<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $table = 'otp_verifications';
    
    protected $fillable = [
        'user_id',
        'email',
        'otp_code',
        'expires_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired()
    {
        return now()->isAfter($this->expires_at);
    }

    public function isValid($otp)
    {
        if ($this->isExpired()) {
            return false;
        }
        
        if ($this->attempts >= 3) {
            return false;
        }
        
        return $this->otp_code === $otp;
    }
}
