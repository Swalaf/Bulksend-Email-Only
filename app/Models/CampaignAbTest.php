<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignAbTest extends Model
{
    protected $fillable = [
        'campaign_id',
        'variant_a_subject',
        'variant_b_subject',
        'variant_a_html_content',
        'variant_b_html_content',
        'variant_a_open_count',
        'variant_b_open_count',
        'variant_a_click_count',
        'variant_b_click_count',
        'winner',
        'test_started_at',
        'test_ended_at',
        'test_duration_hours',
        'sample_size_per_variant',
    ];

    protected $casts = [
        'test_started_at' => 'datetime',
        'test_ended_at' => 'datetime',
        'variant_a_open_count' => 'integer',
        'variant_b_open_count' => 'integer',
        'variant_a_click_count' => 'integer',
        'variant_b_click_count' => 'integer',
        'sample_size_per_variant' => 'integer',
        'test_duration_hours' => 'integer',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getVariantARate(): float
    {
        $total = $this->variant_a_open_count + $this->variant_a_click_count;
        return $total > 0 ? round(($this->variant_a_click_count / $total) * 100, 2) : 0;
    }

    public function getVariantBRate(): float
    {
        $total = $this->variant_b_open_count + $this->variant_b_click_count;
        return $total > 0 ? round(($this->variant_b_click_count / $total) * 100, 2) : 0;
    }

    public function isComplete(): bool
    {
        return $this->winner !== null;
    }

    public function determineWinner(): string
    {
        $aRate = $this->getVariantARate();
        $bRate = $this->getVariantBRate();

        $this->winner = $aRate > $bRate ? 'a' : ($bRate > $aRate ? 'b' : 'tie');
        $this->test_ended_at = now();
        $this->save();

        return $this->winner;
    }
}
