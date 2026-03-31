<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\Campaign;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalyticController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Get analytics for a specific campaign
     */
    public function campaignAnalytics(Request $request, $campaignId): JsonResponse
    {
        $campaign = Campaign::findOrFail($campaignId);

        // Check if user owns this campaign
        if ($campaign->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $analytics = $this->analyticsService->getCampaignAnalytics($campaign, [
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
            ]);

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get overall user analytics
     */
    public function userAnalytics(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', '30d'); // 7d, 30d, 90d, 1y
            $startDate = $this->getStartDate($period);

            $analytics = $this->analyticsService->getUserAnalytics(Auth::user(), [
                'start_date' => $startDate,
                'end_date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track email events (opens, clicks, etc.)
     */
    public function track(Request $request): JsonResponse
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'subscriber_id' => 'required|exists:subscribers,id',
            'event_type' => 'required|in:open,click,unsubscribe,bounce,complaint',
            'link_url' => 'nullable|url',
            'user_agent' => 'nullable|string',
            'ip_address' => 'nullable|ip',
        ]);

        try {
            $campaign = Campaign::findOrFail($request->campaign_id);

            // Check if user owns this campaign
            if ($campaign->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $analytic = $this->analyticsService->trackEvent([
                'campaign_id' => $request->campaign_id,
                'subscriber_id' => $request->subscriber_id,
                'event_type' => $request->event_type,
                'link_url' => $request->link_url,
                'user_agent' => $request->user_agent,
                'ip_address' => $request->ip_address,
                'tracked_at' => now(),
            ]);

            // Return 1x1 transparent pixel for open tracking
            if ($request->event_type === 'open') {
                $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
                return response($pixel, 200, [
                    'Content-Type' => 'image/gif',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Event tracked successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics summary
     */
    public function summary(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', '30d');
            $startDate = $this->getStartDate($period);

            $summary = [
                'total_campaigns' => Campaign::where('user_id', Auth::id())->count(),
                'total_subscribers' => Auth::user()->subscribers()->count(),
                'total_sends' => Auth::user()->campaigns()->sum('subscribers_count'),
                'total_opens' => Analytic::whereHas('campaign', function ($query) {
                    $query->where('user_id', Auth::id());
                })->where('event_type', 'open')->count(),
                'total_clicks' => Analytic::whereHas('campaign', function ($query) {
                    $query->where('user_id', Auth::id());
                })->where('event_type', 'click')->count(),
                'average_open_rate' => $this->calculateAverageRate('open'),
                'average_click_rate' => $this->calculateAverageRate('click'),
                'period' => $period,
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance over time
     */
    public function performanceOverTime(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', '30d');
            $startDate = $this->getStartDate($period);
            $groupBy = $request->get('group_by', 'day'); // day, week, month

            $performance = $this->analyticsService->getPerformanceOverTime(Auth::user(), [
                'start_date' => $startDate,
                'end_date' => now(),
                'group_by' => $groupBy,
            ]);

            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch performance data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top performing campaigns
     */
    public function topCampaigns(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $metric = $request->get('metric', 'open_rate'); // open_rate, click_rate, conversions

            $campaigns = $this->analyticsService->getTopCampaigns(Auth::user(), $metric, $limit);

            return response()->json([
                'success' => true,
                'data' => $campaigns
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch top campaigns',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export analytics data
     */
    public function export(Request $request): JsonResponse
    {
        $request->validate([
            'campaign_id' => 'nullable|exists:campaigns,id',
            'format' => 'required|in:csv,json',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        try {
            $campaign = null;
            if ($request->has('campaign_id')) {
                $campaign = Campaign::findOrFail($request->campaign_id);
                if ($campaign->user_id !== Auth::id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized'
                    ], 403);
                }
            }

            $data = $this->analyticsService->exportAnalytics($campaign, Auth::user(), [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'format' => $request->format,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Export completed',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to get start date based on period
     */
    private function getStartDate(string $period): Carbon
    {
        return match ($period) {
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => now()->subDays(30),
        };
    }

    /**
     * Calculate average rate for a metric
     */
    private function calculateAverageRate(string $eventType): float
    {
        $totalSends = Auth::user()->campaigns()->sum('subscribers_count');
        if ($totalSends == 0) return 0;

        $totalEvents = Analytic::whereHas('campaign', function ($query) {
            $query->where('user_id', Auth::id());
        })->where('event_type', $eventType)->count();

        return round(($totalEvents / $totalSends) * 100, 2);
    }
}