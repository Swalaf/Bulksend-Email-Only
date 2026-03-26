<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarmupEmail extends Model
{
    protected $fillable = [
        'email_warmup_id',
        'smtp_account_id',
        'recipient_email',
        'subject',
        'type',
        'status',
        'sent_at',
        'delivered_at',
        'opened_at',
        'replied_at',
        'send_order',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    const TYPE_ENGAGEMENT = 'engagement';
    const TYPE_REPLY = 'reply';
    const TYPE_CONFIRMATION = 'confirmation';

    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_OPENED = 'opened';
    const STATUS_REPLIED = 'replied';

    public function warmup()
    {
        return $this->belongsTo(EmailWarmup::class, 'email_warmup_id');
    }

    public function smtpAccount()
    {
        return $this->belongsTo(SmtpAccount::class);
    }

    public function engagementLogs()
    {
        return $this->hasMany(WarmupEngagementLog::class);
    }

    public function markAsSent(): void
    {
        $this->update(['status' => self::STATUS_SENT, 'sent_at' => now()]);
    }

    public function markAsDelivered(): void
    {
        $this->update(['status' => self::STATUS_DELIVERED, 'delivered_at' => now()]);
    }

    public function markAsOpened(): void
    {
        $this->update(['status' => self::STATUS_OPENED, 'opened_at' => now()]);
    }

    public function markAsReplied(): void
    {
        $this->update(['status' => self::STATUS_REPLIED, 'replied_at' => now()]);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }
}
