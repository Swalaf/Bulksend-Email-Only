<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLinkClick extends Model
{
    protected $fillable = [
        'campaign_link_id',
        'campaign_subscriber_id',
        'subscriber_id',
        'campaign_id',
        'ip_address',
        'user_agent',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function campaignLink()
    {
        return $this->belongsTo(CampaignLink::class);
    }

    public function campaignSubscriber()
    {
        return $this->belongsTo(CampaignSubscriber::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
