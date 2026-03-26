<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLink extends Model
{
    protected $fillable = [
        'campaign_id',
        'url',
        'hash',
        'click_count',
        'unique_click_count',
    ];

    protected $casts = [
        'click_count' => 'integer',
        'unique_click_count' => 'integer',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function clickLogs()
    {
        return $this->hasMany(CampaignLinkClick::class);
    }

    public function getShortUrl(): string
    {
        return route('tracking.click', ['hash' => $this->hash]);
    }

    public function incrementClick(bool $isUnique = false): void
    {
        $this->increment('click_count');
        if ($isUnique) {
            $this->increment('unique_click_count');
        }
    }
}
