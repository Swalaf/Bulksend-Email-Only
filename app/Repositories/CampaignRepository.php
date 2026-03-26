<?php

namespace App\Repositories;

use App\Models\Campaign;

class CampaignRepository extends BaseRepository
{
    public function __construct(Campaign $model)
    {
        parent::__construct($model);
    }

    public function getUserCampaigns(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getByStatus(string $status)
    {
        return $this->model->where('status', $status)->get();
    }

    public function getUserCampaignsByStatus(int $userId, string $status)
    {
        return $this->model->where('user_id', $userId)
            ->where('status', $status)
            ->get();
    }
}
