<?php

namespace App\Services;

use App\Repositories\CampaignRepository;
use App\Repositories\SubscriberRepository;
use App\Repositories\AnalyticRepository;

class CampaignService extends BaseService
{
    protected CampaignRepository $campaignRepository;
    protected SubscriberRepository $subscriberRepository;
    protected AnalyticRepository $analyticRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        SubscriberRepository $subscriberRepository,
        AnalyticRepository $analyticRepository
    ) {
        $this->campaignRepository = $campaignRepository;
        $this->subscriberRepository = $subscriberRepository;
        $this->analyticRepository = $analyticRepository;
    }

    public function createCampaign(int $userId, array $data)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
        ];

        if (!$this->validate($data, $rules)) {
            return null;
        }

        return $this->campaignRepository->create(array_merge($data, [
            'user_id' => $userId,
            'status' => 'draft',
        ]));
    }

    public function getUserCampaigns(int $userId)
    {
        return $this->campaignRepository->getUserCampaigns($userId);
    }

    public function getCampaignStats(int $campaignId): array
    {
        return $this->analyticRepository->getCampaignStats($campaignId);
    }

    public function updateCampaignStatus(int $campaignId, string $status): bool
    {
        $validStatuses = ['draft', 'scheduled', 'sending', 'sent', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            $this->errors[] = 'Invalid status';
            return false;
        }

        try {
            $this->campaignRepository->update($campaignId, [
                'status' => $status,
                'sent_at' => $status === 'sent' ? now() : null,
            ]);
            return true;
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
    }
}
