<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignSendLog extends Model
{
    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'smtp_account_id',
        'message_id',
        'from_address',
        'to_address',
        'subject',
        'status',
        'error_message',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';
    const STATUS_BOUNCED = 'bounced';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function smtpAccount()
    {
        return $this->belongsTo(SmtpAccount::class);
    }

    public function markAsSent(): void
    {
        $this->update(['status' => self::STATUS_SENT, 'sent_at' => now()]);
    }

    public function markAsDelivered(): void
    {
        $this->update(['status' => self::STATUS_DELIVERED, 'delivered_at' => now()]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update(['status' => self::STATUS_FAILED, 'error_message' => $errorMessage]);
    }

    public function markAsOpened(): void
    {
        $this->update(['opened_at' => now()]);
    }

    public function markAsClicked(): void
    {
        $this->update(['clicked_at' => now()]);
    }

    public function isSuccessful(): bool
    {
        return in_array($this->status, [self::STATUS_SENT, self::STATUS_DELIVERED]);
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
}
