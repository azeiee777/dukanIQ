<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    public const PURPOSE_SIGNUP = 'signup_verification';

    public const PURPOSE_LOGIN = 'login';

    public const RESEND_COOLDOWN_SECONDS = 60;

    protected $fillable = [
        'email',
        'otp',
        'purpose',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if OTP is valid (not used and not expired)
     */
    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }

    /**
     * Mark OTP as used
     */
    public function markAsUsed(): bool
    {
        return $this->update(['used' => true]);
    }

    /**
     * Generate a new OTP code
     */
    public static function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Return supported OTP purposes.
     *
     * @return array<int, string>
     */
    public static function supportedPurposes(): array
    {
        return [
            self::PURPOSE_SIGNUP,
            self::PURPOSE_LOGIN,
        ];
    }

    /**
     * Create a new OTP for a specific purpose.
     */
    public static function createForPurpose(string $email, string $purpose): self
    {
        if (!in_array($purpose, self::supportedPurposes(), true)) {
            throw new \InvalidArgumentException('Unsupported OTP purpose.');
        }

        self::where('email', $email)
            ->where('purpose', $purpose)
            ->delete();

        return self::create([
            'email' => $email,
            'otp' => self::generateOtp(),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(10),
        ]);
    }

    /**
     * Find the most recent unused, unexpired OTP for an email and purpose.
     */
    public static function findLatestActiveForPurpose(string $email, string $purpose): ?self
    {
        return self::where('email', $email)
            ->where('purpose', $purpose)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    /**
     * Determine whether the OTP was created recently enough to reuse.
     */
    public function wasRecentlyCreated(int $seconds = self::RESEND_COOLDOWN_SECONDS): bool
    {
        return $this->created_at !== null
            && $this->created_at->greaterThan(now()->subSeconds($seconds));
    }

    /**
     * Create a new OTP for signup verification.
     */
    public static function createForEmailVerification(string $email): self
    {
        return self::createForPurpose($email, self::PURPOSE_SIGNUP);
    }

    /**
     * Create a new OTP for login.
     */
    public static function createForLogin(string $email): self
    {
        return self::createForPurpose($email, self::PURPOSE_LOGIN);
    }

    /**
     * Find valid OTP for email and purpose.
     */
    public static function findValidOtp(string $email, string $otp, string $purpose = self::PURPOSE_SIGNUP): ?self
    {
        return self::where('email', $email)
            ->where('otp', $otp)
            ->where('purpose', $purpose)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
    }
}
