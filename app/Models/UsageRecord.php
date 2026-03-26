<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageRecord extends Model
{
    protected $fillable = [
        'user_id',
        'resource_type',
        'count',
        'limit',
        'period',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'count' => 'integer',
        'limit' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPercentageUsed(): float
    {
        return $this->limit > 0 ? round(($this->count / $this->limit) * 100, 1) : 0;
    }

    public function isOverLimit(): bool
    {
        return $this->limit > 0 && $this->count > $this->limit;
    }

    public function remaining(): int
    {
        return max(0, $this->limit - $this->count);
    }

    public static function getCurrentPeriod(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        
        return [
            'start' => $start,
            'end' => $end,
            'month' => $start->format('Y-m'),
        ];
    }
}
