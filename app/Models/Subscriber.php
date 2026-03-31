<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscriber_list_id',
        'email',
        'first_name',
        'last_name',
        'phone',
        'metadata',
        'status',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lists()
    {
        return $this->belongsToMany(SubscriberList::class, 'subscriber_list_subscriber');
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }
}
