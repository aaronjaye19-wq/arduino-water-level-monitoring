<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }
}
