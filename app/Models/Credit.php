<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'transaction_id',
    ];

    const TYPE_PURCHASE = 'purchase';
    const TYPE_USAGE = 'usage';
    const TYPE_BONUS = 'bonus';
    const TYPE_REFUND = 'refund';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public static function addCredits(int $userId, int $amount, string $type, ?string $description = null): self
    {
        return static::create([
            'user_id' => $userId,
            'amount' => abs($amount),
            'type' => $type,
            'description' => $description,
        ]);
    }

    public static function deductCredits(int $userId, int $amount, string $type, ?string $description = null): self
    {
        return static::create([
            'user_id' => $userId,
            'amount' => -abs($amount),
            'type' => $type,
            'description' => $description,
        ]);
    }

    public static function getBalance(int $userId): int
    {
        return static::where('user_id', $userId)->sum('amount');
    }
}
