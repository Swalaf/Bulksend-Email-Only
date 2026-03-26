<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignDuplicate extends Model
{
    protected $fillable = [
        'original_campaign_id',
        'new_campaign_id',
    ];

    public function originalCampaign()
    {
        return $this->belongsTo(Campaign::class, 'original_campaign_id');
    }

    public function newCampaign()
    {
        return $this->belongsTo(Campaign::class, 'new_campaign_id');
    }
}
