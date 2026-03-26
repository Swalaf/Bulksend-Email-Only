<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MarketplaceSmtpAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'purchase_id',
        'listing_id',
        'name',
        'host',
        'port',
        'encryption',
        'from_address',
        'from_name',
        'username',
        'password',
        'daily_limit',
        'monthly_limit',
        'emails_sent_today',
        'emails_sent_month',
        'is_active',
    ];

    protected $casts = [
        'daily_limit' => 'integer',
        'monthly_limit' => 'integer',
        'emails_sent_today' => 'integer',
        'emails_sent_month' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $encryptedFields = ['username', 'password'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->encryptedFields as $field) {
                if ($model->{$field} && !self::isEncrypted($model->{$field})) {
                    $model->{$field} = 'enc:' . Crypt::encryptString($model->{$field});
                }
            }
        });
    }

    public static function isEncrypted(string $value): bool
    {
        return str_starts_with($value, 'enc:');
    }

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

    public function getMailerConfig(): array
    {
        return [
            'transport' => 'smtp',
            'host' => $this->host,
            'port' => $this->port,
            'encryption' => $this->encryption,
            'username' => $this->getDecryptedUsername(),
            'password' => $this->getDecryptedPassword(),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->belongsTo(MarketplacePurchase::class);
    }

    public function listing()
    {
        return $this->belongsTo(MarketplaceListing::class);
    }

    public function canSendToday(): bool
    {
        return $this->emails_sent_today < $this->daily_limit;
    }

    public function incrementSentCount(): void
    {
        $this->increment('emails_sent_today');
        $this->increment('emails_sent_month');
    }
}
