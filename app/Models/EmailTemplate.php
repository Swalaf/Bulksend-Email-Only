<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'subject',
        'html_content',
        'plain_text_content',
        'category',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeDefaults($query)
    {
        return $query->where('is_default', true);
    }

    public function setAsDefault(): void
    {
        static::where('user_id', $this->user_id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }
}
