<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignSendLog;
use App\Models\SmtpAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getOverviewMetrics(int $userId): array
    {
        $campaigns = Campaign::where('user_id', $userId)->where('status', Campaign::STATUS_SENT);
        
        $totalCampaigns = (clone $campaigns)->count();
        $totalSent = (clone $campaigns)->sum('sent_count');
        $totalOpened = (clone $campaigns)->sum('opened_count');
        $totalClicked = (clone $campaigns)->sum('clicked_count');
        $totalBounced = (clone $campaigns)->sum('bounced_count');

        return [
            'total_campaigns' => $totalCampaigns,
            'total_sent' => $totalSent,
            'total_opened' => $totalOpened,
            'total_clicked' => $totalClicked,
            'total_bounced' => $totalBounced,
            'open_rate' => $totalSent > 0 ? round(($totalOpened / $totalSent) * 100, 2) : 0,
            'click_rate' => $totalSent > 0 ? round(($totalClicked / $totalSent) * 100, 2) : 0,
            'bounce_rate' => $totalSent > 0 ? round(($totalBounced / $totalSent) * 100, 2) : 0,
        ];
    }

    public function getTimeSeriesData(int $userId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $data = CampaignSendLog::selectRaw('DATE(sent_at) as date')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "sent" OR status = "delivered" THEN 1 ELSE 0 END) as delivered')
            ->selectRaw('SUM(CASE WHEN opened_at IS NOT NULL THEN 1 ELSE 0 END) as opened')
            ->selectRaw('SUM(CASE WHEN clicked_at IS NOT NULL THEN 1 ELSE 0 END) as clicked')
            ->whereHas('campaign', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('sent_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $result = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte(Carbon::now())) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayData = $data->firstWhere('date', $dateStr);
            
            $result[] = [
                'date' => $currentDate->format('M d'),
                'full_date' => $dateStr,
                'sent' => $dayData?->total ?? 0,
                'delivered' => $dayData?->delivered ?? 0,
                'opened' => $dayData?->opened ?? 0,
                'clicked' => $dayData?->clicked ?? 0,
            ];
            
            $currentDate->addDay();
        }

        return $result;
    }

    public function getCampaignPerformance(int $userId, int $limit = 10): array
    {
        return Campaign::where('user_id', $userId)
            ->where('status', Campaign::STATUS_SENT)
            ->orderBy('sent_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'subject' => $campaign->subject,
                    'sent' => $campaign->sent_count,
                    'opened' => $campaign->opened_count,
                    'clicked' => $campaign->clicked_count,
                    'bounced' => $campaign->bounced_count,
                    'open_rate' => $campaign->getOpenRate(),
                    'click_rate' => $campaign->getClickRate(),
                    'sent_at' => $campaign->completed_at?->format('M d, Y'),
                ];
            })
            ->toArray();
    }

    public function getSmtpUsage(int $userId): array
    {
        $smtpAccounts = SmtpAccount::where('user_id', $userId)->get();
        
        return $smtpAccounts->map(function ($account) {
            $usage = $account->sentEmails()->count();
            $limit = $account->daily_limit ?? 0;
            
            return [
                'id' => $account->id,
                'name' => $account->name,
                'from_address' => $account->from_address,
                'is_active' => $account->is_active,
                'usage_today' => $account->sentEmails()
                    ->whereDate('sent_at', today())
                    ->count(),
                'daily_limit' => $limit,
                'usage_percentage' => $limit > 0 ? min(100, round(($usage / $limit) * 100)) : 0,
            ];
        })->toArray();
    }

    public function getRealtimeStats(int $userId): array
    {
        $sendingCampaigns = Campaign::where('user_id', $userId)
            ->where('status', Campaign::STATUS_SENDING)
            ->get();

        $totalInQueue = 0;
        $sendingData = [];

        foreach ($sendingCampaigns as $campaign) {
            $pending = $campaign->campaignSubscribers()
                ->where('status', 'pending')
                ->count();
            
            $totalInQueue += $pending;
            
            $sendingData[] = [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'sent' => $campaign->sent_count,
                'total' => $campaign->total_recipients,
                'progress' => $campaign->getProgress(),
            ];
        }

        return [
            'active_campaigns' => $sendingCampaigns->count(),
            'total_in_queue' => $totalInQueue,
            'campaigns' => $sendingData,
        ];
    }

    public function getEngagementFunnel(int $userId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);

        $campaigns = Campaign::where('user_id', $userId)
            ->where('status', Campaign::STATUS_SENT)
            ->where('completed_at', '>=', $startDate)
            ->get();

        $totalSent = $campaigns->sum('sent_count');
        $totalDelivered = $totalSent - $campaigns->sum('bounced_count');
        $totalOpened = $campaigns->sum('opened_count');
        $totalClicked = $campaigns->sum('clicked_count');

        return [
            'sent' => $totalSent,
            'delivered' => $totalDelivered,
            'opened' => $totalOpened,
            'clicked' => $totalClicked,
            'rates' => [
                'delivery_rate' => $totalSent > 0 ? round(($totalDelivered / $totalSent) * 100, 1) : 0,
                'open_rate' => $totalDelivered > 0 ? round(($totalOpened / $totalDelivered) * 100, 1) : 0,
                'click_rate' => $totalOpened > 0 ? round(($totalClicked / $totalOpened) * 100, 1) : 0,
            ],
        ];
    }

    public function getTopPerformingCampaigns(int $userId, string $metric = 'open_rate', int $limit = 5): array
    {
        $query = Campaign::where('user_id', $userId)
            ->where('status', Campaign::STATUS_SENT)
            ->where('sent_count', '>', 0);

        $campaigns = match($metric) {
            'open_rate' => (clone $query)->orderByDesc('opened_count')->orderByDesc('sent_count')->limit($limit)->get(),
            'click_rate' => (clone $query)->orderByDesc('clicked_count')->orderByDesc('sent_count')->limit($limit)->get(),
            'total_sent' => (clone $query)->orderByDesc('sent_count')->limit($limit)->get(),
            default => (clone $query)->orderByDesc('sent_count')->limit($limit)->get(),
        };

        return $campaigns->map(function ($campaign) {
            return [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'sent' => $campaign->sent_count,
                'opened' => $campaign->opened_count,
                'clicked' => $campaign->clicked_count,
                'open_rate' => $campaign->getOpenRate(),
                'click_rate' => $campaign->getClickRate(),
            ];
        })->toArray();
    }

    public function getSubscriberGrowth(int $userId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // This would typically use a subscribers table - returning mock for now
        $data = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte(Carbon::now())) {
            $data[] = [
                'date' => $currentDate->format('M d'),
                'subscribers' => rand(50, 200),
                'unsubscribes' => rand(0, 10),
            ];
            $currentDate->addDay();
        }

        return $data;
    }
}
