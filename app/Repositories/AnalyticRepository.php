<?php

namespace App\Repositories;

use App\Models\Analytic;

class AnalyticRepository extends BaseRepository
{
    public function __construct(Analytic $model)
    {
        parent::__construct($model);
    }

    public function getByCampaign(int $campaignId)
    {
        return $this->model->where('campaign_id', $campaignId)->get();
    }

    public function getByEventType(string $eventType)
    {
        return $this->model->where('event_type', $eventType)->get();
    }

    public function getCampaignEventsByType(int $campaignId, string $eventType)
    {
        return $this->model->where('campaign_id', $campaignId)
            ->where('event_type', $eventType)
            ->get();
    }

    public function getCampaignStats(int $campaignId): array
    {
        return [
            'opened' => $this->model->where('campaign_id', $campaignId)
                ->where('event_type', 'opened')->count(),
            'clicked' => $this->model->where('campaign_id', $campaignId)
                ->where('event_type', 'clicked')->count(),
            'bounced' => $this->model->where('campaign_id', $campaignId)
                ->where('event_type', 'bounced')->count(),
            'unsubscribed' => $this->model->where('campaign_id', $campaignId)
                ->where('event_type', 'unsubscribed')->count(),
        ];
    }
}
