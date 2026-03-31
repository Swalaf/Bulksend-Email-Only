<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $campaigns = Campaign::where('user_id', Auth::id())
            ->with(['smtpAccount', 'analytics'])
            ->when($request->has('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'smtp_account_id' => 'required|exists:smtp_accounts,id',
            'html_content' => 'required|string',
            'plain_text_content' => 'nullable|string',
            'subscriber_list_ids' => 'required|array',
            'subscriber_list_ids.*' => 'exists:subscriber_lists,id',
            'schedule_date' => 'nullable|date|after:now',
        ]);

        try {
            $campaign = $this->campaignService->createCampaign($request->all(), Auth::user());

            return response()->json([
                'success' => true,
                'message' => 'Campaign created successfully',
                'data' => $campaign->load(['smtpAccount', 'subscriberLists'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create campaign',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign): JsonResponse
    {
        // Check if user owns this campaign
        if ($campaign->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $campaign->load(['smtpAccount', 'analytics', 'subscriberLists', 'abTest.variations']);

        return response()->json([
            'success' => true,
            'data' => $campaign
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign): JsonResponse
    {
        // Check if user owns this campaign
        if ($campaign->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'subject' => 'sometimes|required|string|max:255',
            'html_content' => 'sometimes|required|string',
            'plain_text_content' => 'nullable|string',
        ]);

        try {
            $campaign->update($request->only(['name', 'subject', 'html_content', 'plain_text_content']));

            return response()->json([
                'success' => true,
                'message' => 'Campaign updated successfully',
                'data' => $campaign
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update campaign',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign): JsonResponse
    {
        // Check if user owns this campaign
        if ($campaign->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Only allow deletion of draft campaigns
        if ($campaign->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete campaigns that have been sent'
            ], 422);
        }

        try {
            $campaign->delete();

            return response()->json([
                'success' => true,
                'message' => 'Campaign deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete campaign',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a campaign
     */
    public function send(Request $request, Campaign $campaign): JsonResponse
    {
        // Check if user owns this campaign
        if ($campaign->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($campaign->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Campaign has already been sent'
            ], 422);
        }

        try {
            $this->campaignService->sendCampaign($campaign);

            return response()->json([
                'success' => true,
                'message' => 'Campaign queued for sending'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send campaign',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate a campaign
     */
    public function duplicate(Campaign $campaign): JsonResponse
    {
        // Check if user owns this campaign
        if ($campaign->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $duplicate = $this->campaignService->duplicateCampaign($campaign);

            return response()->json([
                'success' => true,
                'message' => 'Campaign duplicated successfully',
                'data' => $duplicate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate campaign',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}