<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'event_type',
        'url',
        'ip_address',
        'user_agent',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
