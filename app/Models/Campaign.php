<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'smtp_account_id',
        'name',
        'subject',
        'content',
        'html_content',
        'plain_text_content',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'batch_size',
        'batch_delay',
        'total_recipients',
        'sent_count',
        'opened_count',
        'clicked_count',
        'bounced_count',
        'unsubscribed_count',
        'settings',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'opened_count' => 'integer',
        'clicked_count' => 'integer',
        'bounced_count' => 'integer',
        'unsubscribed_count' => 'integer',
        'batch_size' => 'integer',
        'batch_delay' => 'integer',
        'settings' => 'array',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_PAUSED = 'paused';
    const STATUS_CANCELLED = 'cancelled';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function smtpAccount()
    {
        return $this->belongsTo(SmtpAccount::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class, 'campaign_subscribers')
            ->withPivot(['status', 'sent_at', 'opened_at', 'clicked_at', 'bounced_at', 'tracking_token', 'message_id'])
            ->withTimestamps();
    }

    public function campaignSubscribers()
    {
        return $this->hasMany(CampaignSubscriber::class);
    }

    public function sendLogs()
    {
        return $this->hasMany(CampaignSendLog::class);
    }

    public function abTest()
    {
        return $this->hasOne(CampaignAbTest::class);
    }

    public function links()
    {
        return $this->hasMany(CampaignLink::class);
    }

    public function duplicateRelation()
    {
        return $this->hasMany(CampaignDuplicate::class, 'original_campaign_id');
    }

    public function isDraft(): bool { return $this->status === self::STATUS_DRAFT; }
    public function isScheduled(): bool { return $this->status === self::STATUS_SCHEDULED; }
    public function isSending(): bool { return $this->status === self::STATUS_SENDING; }
    public function isSent(): bool { return $this->status === self::STATUS_SENT; }
    public function canEdit(): bool { return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SCHEDULED]); }
    public function canSend(): bool { return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SCHEDULED]) && $this->total_recipients > 0; }
    public function canPause(): bool { return $this->status === self::STATUS_SENDING; }

    public function getOpenRate(): float
    {
        return $this->sent_count > 0 ? round(($this->opened_count / $this->sent_count) * 100, 2) : 0;
    }

    public function getClickRate(): float
    {
        return $this->sent_count > 0 ? round(($this->clicked_count / $this->sent_count) * 100, 2) : 0;
    }

    public function getBounceRate(): float
    {
        return $this->sent_count > 0 ? round(($this->bounced_count / $this->sent_count) * 100, 2) : 0;
    }

    public function getProgress(): int
    {
        return $this->total_recipients > 0 ? round(($this->sent_count / $this->total_recipients) * 100) : 0;
    }

    public function incrementSent(): void { $this->increment('sent_count'); }
    public function incrementOpened(): void { $this->increment('opened_count'); }
    public function incrementClicked(): void { $this->increment('clicked_count'); }
    public function incrementBounced(): void { $this->increment('bounced_count'); }
    public function incrementUnsubscribed(): void { $this->increment('unsubscribed_count'); }

    public function startSending(): void
    {
        $this->update(['status' => self::STATUS_SENDING, 'started_at' => now()]);
    }

    public function pauseSending(): void
    {
        $this->update(['status' => self::STATUS_PAUSED]);
    }

    public function resumeSending(): void
    {
        $this->update(['status' => self::STATUS_SENDING]);
    }

    public function completeSending(): void
    {
        $this->update(['status' => self::STATUS_SENT, 'completed_at' => now()]);
    }

    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    public function createDuplicate(): Campaign
    {
        $newCampaign = $this->replicate();
        $newCampaign->name = $this->name . ' (Copy)';
        $newCampaign->status = self::STATUS_DRAFT;
        $newCampaign->sent_count = 0;
        $newCampaign->opened_count = 0;
        $newCampaign->clicked_count = 0;
        $newCampaign->bounced_count = 0;
        $newCampaign->unsubscribed_count = 0;
        $newCampaign->scheduled_at = null;
        $newCampaign->started_at = null;
        $newCampaign->completed_at = null;
        $newCampaign->save();

        CampaignDuplicate::create([
            'original_campaign_id' => $this->id,
            'new_campaign_id' => $newCampaign->id,
        ]);

        return $newCampaign;
    }

    public function scopeDraft($query) { return $query->where('status', self::STATUS_DRAFT); }
    public function scopeScheduled($query) { return $query->where('status', self::STATUS_SCHEDULED); }
    public function scopeSending($query) { return $query->where('status', self::STATUS_SENDING); }
    public function scopeSent($query) { return $query->where('status', self::STATUS_SENT); }
    public function scopeUpcoming($query) { return $query->where('status', self::STATUS_SCHEDULED)->where('scheduled_at', '>', now()); }
}
