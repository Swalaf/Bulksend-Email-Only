<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignSubscriber extends Model
{
    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'status',
        'sent_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'tracking_token',
        'message_id',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_OPENED = 'opened';
    const STATUS_CLICKED = 'clicked';
    const STATUS_BOUNCED = 'bounced';
    const STATUS_UNSUBSCRIBED = 'unsubscribed';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function markAsSent(string $messageId): void
    {
        $this->update(['status' => self::STATUS_SENT, 'sent_at' => now(), 'message_id' => $messageId]);
    }

    public function markAsOpened(): void
    {
        if ($this->status !== self::STATUS_OPENED) {
            $this->update(['status' => self::STATUS_OPENED, 'opened_at' => now()]);
            $this->campaign->incrementOpened();
        }
    }

    public function markAsClicked(): void
    {
        $this->update(['status' => self::STATUS_CLICKED, 'clicked_at' => now()]);
        $this->campaign->incrementClicked();
    }

    public function markAsBounced(): void
    {
        $this->update(['status' => self::STATUS_BOUNCED, 'bounced_at' => now()]);
        $this->campaign->incrementBounced();
    }

    public function markAsUnsubscribed(): void
    {
        $this->update(['status' => self::STATUS_UNSUBSCRIBED]);
        $this->campaign->incrementUnsubscribed();
    }
}
