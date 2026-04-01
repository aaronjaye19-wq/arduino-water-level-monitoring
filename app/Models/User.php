<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'mfa_code',
        'mfa_code_expires_at',
        'mfa_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'mfa_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mfa_code_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Email verifications relationship
     */
    public function emailVerifications(): HasMany
    {
        return $this->hasMany(EmailVerification::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if email is verified
     */
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Generate MFA code
     */
    public function generateMfaCode(): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update([
            'mfa_code' => $code,
            'mfa_code_expires_at' => now()->addMinutes(5),
            'mfa_verified' => false,
        ]);
        return $code;
    }

    /**
     * Verify MFA code
     */
    public function verifyMfaCode(string $code): bool
    {
        if ($this->mfa_code === $code && $this->mfa_code_expires_at > now()) {
            $this->update([
                'mfa_verified' => true,
                'mfa_code' => null,
                'mfa_code_expires_at' => null,
            ]);
            return true;
        }
        return false;
    }
}
