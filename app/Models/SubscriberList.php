<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'subscriber_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscriber_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class, 'subscriber_list_subscriber');
    }
}
