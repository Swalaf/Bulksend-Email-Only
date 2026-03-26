<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    public function index()
    {
        $userId = Auth::id();
        
        $overview = $this->analyticsService->getOverviewMetrics($userId);
        $timeSeries = $this->analyticsService->getTimeSeriesData($userId, 30);
        $topCampaigns = $this->analyticsService->getCampaignPerformance($userId, 10);
        $smtpUsage = $this->analyticsService->getSmtpUsage($userId);
        $realtime = $this->analyticsService->getRealtimeStats($userId);
        $funnel = $this->analyticsService->getEngagementFunnel($userId, 30);

        return view('analytics.index', compact(
            'overview',
            'timeSeries',
            'topCampaigns',
            'smtpUsage',
            'realtime',
            'funnel'
        ));
    }

    public function overview(Request $request)
    {
        $userId = Auth::id();
        $days = $request->get('days', 30);

        $data = [
            'overview' => $this->analyticsService->getOverviewMetrics($userId),
            'time_series' => $this->analyticsService->getTimeSeriesData($userId, $days),
            'funnel' => $this->analyticsService->getEngagementFunnel($userId, $days),
        ];

        return response()->json($data);
    }

    public function campaigns(Request $request)
    {
        $userId = Auth::id();
        $limit = $request->get('limit', 10);
        $metric = $request->get('metric', 'sent');

        $campaigns = $this->analyticsService->getCampaignPerformance($userId, $limit);

        return response()->json([
            'campaigns' => $campaigns,
        ]);
    }

    public function smtp()
    {
        $userId = Auth::id();
        $usage = $this->analyticsService->getSmtpUsage($userId);

        return response()->json([
            'smtp_accounts' => $usage,
        ]);
    }

    public function realtime()
    {
        $userId = Auth::id();
        $stats = $this->analyticsService->getRealtimeStats($userId);

        return response()->json($stats);
    }

    public function chart(Request $request)
    {
        $userId = Auth::id();
        $type = $request->get('type', 'time_series');
        $days = $request->get('days', 30);

        $data = match($type) {
            'time_series' => $this->analyticsService->getTimeSeriesData($userId, $days),
            'funnel' => $this->analyticsService->getEngagementFunnel($userId, $days),
            'subscriber_growth' => $this->analyticsService->getSubscriberGrowth($userId, $days),
            default => [],
        };

        return response()->json($data);
    }
}
