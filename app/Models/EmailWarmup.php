<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailWarmup extends Model
{
    protected $fillable = [
        'smtp_account_id',
        'status',
        'current_daily_limit',
        'target_daily_limit',
        'current_day',
        'total_days',
        'started_at',
        'paused_at',
        'completed_at',
        'settings',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
        'completed_at' => 'datetime',
        'current_daily_limit' => 'integer',
        'target_daily_limit' => 'integer',
        'current_day' => 'integer',
        'total_days' => 'integer',
        'settings' => 'array',
    ];

    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';
    const STATUS_PAUSED = 'paused';
    const STATUS_COMPLETED = 'completed';

    public function smtpAccount()
    {
        return $this->belongsTo(SmtpAccount::class);
    }

    public function warmupEmails()
    {
        return $this->hasMany(WarmupEmail::class);
    }

    public function dailyStats()
    {
        return $this->hasMany(WarmupDailyStat::class);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPaused(): bool
    {
        return $this->status === self::STATUS_PAUSED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canSend(): bool
    {
        return $this->isActive() && $this->current_day <= $this->total_days;
    }

    public function getProgress(): float
    {
        return round(($this->current_day / $this->total_days) * 100, 1);
    }

    public function getTodaySentCount(): int
    {
        return $this->warmupEmails()
            ->whereDate('sent_at', today())
            ->count();
    }

    public function getTodayRemainingQuota(): int
    {
        return max(0, $this->current_daily_limit - $this->getTodaySentCount());
    }

    public function calculateNextDayLimit(): int
    {
        $progress = $this->current_day / $this->total_days;
        $increment = ($this->target_daily_limit - $this->current_daily_limit) / ($this->total_days - $this->current_day);
        
        return min(
            $this->target_daily_limit,
            (int) ceil($this->current_daily_limit + ($increment * (1 - $progress * 0.5)))
        );
    }

    public function incrementDay(): void
    {
        $this->update([
            'current_day' => $this->current_day + 1,
            'current_daily_limit' => $this->calculateNextDayLimit(),
        ]);
    }

    public function start(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'started_at' => now(),
            'current_day' => 1,
            'current_daily_limit' => 10,
        ]);
    }

    public function pause(): void
    {
        $this->update([
            'status' => self::STATUS_PAUSED,
            'paused_at' => now(),
        ]);
    }

    public function resume(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'paused_at' => null,
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }
}
