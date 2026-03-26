<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarmupDailyStat extends Model
{
    protected $fillable = [
        'email_warmup_id',
        'date',
        'emails_sent',
        'emails_delivered',
        'emails_opened',
        'emails_replied',
        'emails_clicked',
        'daily_limit',
    ];

    protected $casts = [
        'date' => 'date',
        'emails_sent' => 'integer',
        'emails_delivered' => 'integer',
        'emails_opened' => 'integer',
        'emails_replied' => 'integer',
        'emails_clicked' => 'integer',
        'daily_limit' => 'integer',
    ];

    public function warmup()
    {
        return $this->belongsTo(EmailWarmup::class);
    }

    public function getDeliveryRate(): float
    {
        return $this->emails_sent > 0 
            ? round(($this->emails_delivered / $this->emails_sent) * 100, 1) 
            : 0;
    }

    public function getOpenRate(): float
    {
        return $this->emails_delivered > 0 
            ? round(($this->emails_opened / $this->emails_delivered) * 100, 1) 
            : 0;
    }

    public function getReplyRate(): float
    {
        return $this->emails_delivered > 0 
            ? round(($this->emails_replied / $this->emails_delivered) * 100, 1) 
            : 0;
    }

    public function getClickRate(): float
    {
        return $this->emails_opened > 0 
            ? round(($this->emails_clicked / $this->emails_opened) * 100, 1) 
            : 0;
    }
}
