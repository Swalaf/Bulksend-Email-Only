<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SmtpAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_default',
        'is_active',
        'daily_limit',
        'monthly_limit',
        'is_verified',
        'last_tested_at',
        'last_test_error',
        'status',
        'emails_sent_today',
        'emails_sent_month',
        'last_used_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'daily_limit' => 'integer',
        'monthly_limit' => 'integer',
        'emails_sent_today' => 'integer',
        'emails_sent_month' => 'integer',
        'port' => 'integer',
        'last_tested_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    /**
     * Encrypted fields that need automatic encryption/decryption
     */
    protected $encryptedFields = ['username', 'password'];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically encrypt fields before saving
        static::saving(function ($model) {
            foreach ($model->encryptedFields as $field) {
                if ($model->{$field} && !self::isEncrypted($model->{$field})) {
                    $model->{$field} = Crypt::encryptString($model->{$field});
                }
            }
        });
    }

    /**
     * Check if a string is already encrypted
     */
    public static function isEncrypted(string $value): bool
    {
        try {
            return strlen($value) > 20 && base64_decode($value, true) !== false && str_starts_with($value, 'enc:');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get decrypted username
     */
    public function getDecryptedUsername(): string
    {
        try {
            if (self::isEncrypted($this->username)) {
                return Crypt::decryptString(str_replace('enc:', '', $this->username));
            }
            return $this->username;
        } catch (\Exception $e) {
            return $this->username;
        }
    }

    /**
     * Get decrypted password
     */
    public function getDecryptedPassword(): string
    {
        try {
            if (self::isEncrypted($this->password)) {
                return Crypt::decryptString(str_replace('enc:', '', $this->password));
            }
            return $this->password;
        } catch (\Exception $e) {
            return $this->password;
        }
    }

    /**
     * Get config array for SwiftMailer
     */
    public function getMailerConfig(): array
    {
        return [
            'transport' => 'smtp',
            'host' => $this->host,
            'port' => $this->port,
            'encryption' => $this->encryption,
            'username' => $this->getDecryptedUsername(),
            'password' => $this->getDecryptedPassword(),
            'timeout' => null,
            'local_domain' => null,
        ];
    }

    /**
     * Get from address with fallback
     */
    public function getFromAddress(): string
    {
        return $this->from_address;
    }

    /**
     * Get from name with fallback to user business name
     */
    public function getFromName(): string
    {
        return $this->from_name ?? $this->user?->business_name ?? config('app.name');
    }

    /**
     * Check if user can send more emails today
     */
    public function canSendToday(): bool
    {
        return $this->emails_sent_today < $this->daily_limit;
    }

    /**
     * Check if user can send more emails this month
     */
    public function canSendMonth(): bool
    {
        return $this->emails_sent_month < $this->monthly_limit;
    }

    /**
     * Increment sent count
     */
    public function incrementSentCount(): void
    {
        $this->increment('emails_sent_today');
        $this->increment('emails_sent_month');
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Reset daily counter if needed
     */
    public function resetDailyCountIfNeeded(): void
    {
        if ($this->last_used_at && $this->last_used_at->isYesterday()) {
            $this->update(['emails_sent_today' => 0]);
        }
    }

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Campaigns relationship
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Sent emails relationship
     */
    public function sentEmails()
    {
        return $this->hasMany(CampaignSendLog::class);
    }

    /**
     * Scope for default account
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for verified accounts
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Set as default SMTP
     */
    public function setAsDefault(): void
    {
        // Remove default from all other accounts for this user
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Set this as default
        $this->update(['is_default' => true]);
    }

    /**
     * Mark as verified
     */
    public function markAsVerified(): void
    {
        $this->update([
            'is_verified' => true,
            'status' => 'verified',
            'last_tested_at' => now(),
            'last_test_error' => null,
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'is_verified' => false,
            'status' => 'failed',
            'last_tested_at' => now(),
            'last_test_error' => $error,
        ]);
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'verified' => 'green',
            'failed' => 'red',
            'suspended' => 'yellow',
            default => 'gray',
        };
    }
}
