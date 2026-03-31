<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\SubscriberList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $subscribers = Subscriber::where('user_id', Auth::id())
            ->with(['lists'])
            ->when($request->has('list_id'), function ($query) use ($request) {
                return $query->whereHas('lists', function ($q) use ($request) {
                    $q->where('subscriber_lists.id', $request->list_id);
                });
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $search = $request->search;
                return $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', '%' . $search . '%')
                      ->orWhere('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            })
            ->when($request->has('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $subscribers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email,NULL,id,user_id,' . Auth::id(),
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'list_ids' => 'nullable|array',
            'list_ids.*' => 'exists:subscriber_lists,id',
            'custom_fields' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subscriber = Subscriber::create([
                'user_id' => Auth::id(),
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'custom_fields' => $request->custom_fields ?? [],
                'status' => 'active',
            ]);

            // Attach to lists if provided
            if ($request->has('list_ids') && is_array($request->list_ids)) {
                // Verify user owns these lists
                $ownedLists = SubscriberList::where('user_id', Auth::id())
                    ->whereIn('id', $request->list_ids)
                    ->pluck('id');

                $subscriber->lists()->attach($ownedLists);
            }

            return response()->json([
                'success' => true,
                'message' => 'Subscriber created successfully',
                'data' => $subscriber->load('lists')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subscriber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscriber $subscriber): JsonResponse
    {
        // Check if user owns this subscriber
        if ($subscriber->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $subscriber->load(['lists', 'campaignSubscriber'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscriber $subscriber): JsonResponse
    {
        // Check if user owns this subscriber
        if ($subscriber->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|required|email|unique:subscribers,email,' . $subscriber->id . ',id,user_id,' . Auth::id(),
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:active,unsubscribed,bounced',
            'list_ids' => 'nullable|array',
            'list_ids.*' => 'exists:subscriber_lists,id',
            'custom_fields' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subscriber->update($request->only([
                'email', 'first_name', 'last_name', 'status', 'custom_fields'
            ]));

            // Update lists if provided
            if ($request->has('list_ids')) {
                // Verify user owns these lists
                $ownedLists = SubscriberList::where('user_id', Auth::id())
                    ->whereIn('id', $request->list_ids)
                    ->pluck('id');

                $subscriber->lists()->sync($ownedLists);
            }

            return response()->json([
                'success' => true,
                'message' => 'Subscriber updated successfully',
                'data' => $subscriber->load('lists')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subscriber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber): JsonResponse
    {
        // Check if user owns this subscriber
        if ($subscriber->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $subscriber->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subscriber deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subscriber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk import subscribers
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'subscribers' => 'required|array',
            'subscribers.*.email' => 'required|email',
            'subscribers.*.first_name' => 'nullable|string|max:255',
            'subscribers.*.last_name' => 'nullable|string|max:255',
            'list_id' => 'nullable|exists:subscriber_lists,id',
        ]);

        try {
            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($request->subscribers as $index => $subscriberData) {
                try {
                    // Check if subscriber already exists
                    $existing = Subscriber::where('user_id', Auth::id())
                        ->where('email', $subscriberData['email'])
                        ->first();

                    if ($existing) {
                        $skipped++;
                        continue;
                    }

                    $subscriber = Subscriber::create([
                        'user_id' => Auth::id(),
                        'email' => $subscriberData['email'],
                        'first_name' => $subscriberData['first_name'] ?? null,
                        'last_name' => $subscriberData['last_name'] ?? null,
                        'custom_fields' => [],
                        'status' => 'active',
                    ]);

                    // Attach to list if specified
                    if ($request->has('list_id')) {
                        $list = SubscriberList::where('user_id', Auth::id())
                            ->where('id', $request->list_id)
                            ->first();

                        if ($list) {
                            $subscriber->lists()->attach($list->id);
                        }
                    }

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Import completed. {$imported} imported, {$skipped} skipped.",
                'data' => [
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'errors' => $errors
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unsubscribe a subscriber
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ]);

        try {
            $subscriber = Subscriber::where('email', $request->email)->first();

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $subscriber->update(['status' => 'unsubscribed']);

            return response()->json([
                'success' => true,
                'message' => 'Successfully unsubscribed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}