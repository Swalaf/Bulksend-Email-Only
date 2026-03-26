<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarmupEngagementLog extends Model
{
    protected $fillable = [
        'warmup_email_id',
        'email_warmup_id',
        'engagement_type',
        'engaged_at',
    ];

    protected $casts = [
        'engaged_at' => 'datetime',
    ];

    const TYPE_OPEN = 'open';
    const TYPE_CLICK = 'click';
    const TYPE_REPLY = 'reply';

    public function warmupEmail()
    {
        return $this->belongsTo(WarmupEmail::class, 'warmup_email_id');
    }

    public function warmup()
    {
        return $this->belongsTo(EmailWarmup::class, 'email_warmup_id');
    }
}
