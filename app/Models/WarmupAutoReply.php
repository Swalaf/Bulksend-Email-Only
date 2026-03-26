<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarmupAutoReply extends Model
{
    protected $fillable = [
        'email_pattern',
        'reply_subject',
        'reply_body',
        'response_delay_hours',
        'is_active',
    ];

    protected $casts = [
        'response_delay_hours' => 'integer',
        'is_active' => 'boolean',
    ];

    public function matches(string $email): bool
    {
        return str_contains($email, $this->email_pattern);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
