<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetTokenDetail extends Model
{
    use HasFactory;

    protected $table = 'password_reset_token_details';
    protected $fillable = ['user_id', 'token', 'expires_at'];
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public function isExpired()
    {
        return now()->isAfter($this->expires_at);
    }
}
