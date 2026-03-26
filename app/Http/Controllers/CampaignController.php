<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCampaignBatch;
use App\Jobs\ProcessScheduledCampaign;
use App\Models\Campaign;
use App\Models\CampaignAbTest;
use App\Models\CampaignLink;
use App\Models\CampaignSubscriber;
use App\Models\Subscriber;
use App\Models\SmtpAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $smtpAccounts = SmtpAccount::where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        return view('campaigns.create', compact('smtpAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'html_content' => 'required|string',
            'plain_text_content' => 'nullable|string',
            'smtp_account_id' => 'required|exists:smtp_accounts,id',
            'scheduled_at' => 'nullable|date|after:now',
            'batch_size' => 'nullable|integer|min:1|max:500',
            'batch_delay' => 'nullable|integer|min:0|max:300',
        ]);

        $campaign = Campaign::create([
            'user_id' => auth()->id(),
            'smtp_account_id' => $validated['smtp_account_id'],
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'html_content' => $this->processLinks($validated['html_content']),
            'plain_text_content' => $validated['plain_text_content'] ?? strip_tags($validated['html_content']),
            'status' => $validated['scheduled_at'] ? Campaign::STATUS_SCHEDULED : Campaign::STATUS_DRAFT,
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'batch_size' => $validated['batch_size'] ?? 100,
            'batch_delay' => $validated['batch_delay'] ?? 5,
        ]);

        if ($validated['scheduled_at']) {
            ProcessScheduledCampaign::dispatch($campaign)->delay($campaign->scheduled_at);
        }

        return redirect()->route('campaigns.show', $campaign->id)
            ->with('success', 'Campaign created successfully');
    }

    public function show(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        $campaign->load(['smtpAccount', 'campaignSubscribers', 'links']);

        return view('campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        if (!$campaign->canEdit()) {
            return redirect()->route('campaigns.show', $campaign->id)
                ->with('error', 'Cannot edit a campaign that is already sent or sending');
        }

        $smtpAccounts = SmtpAccount::where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        return view('campaigns.edit', compact('campaign', 'smtpAccounts'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        if (!$campaign->canEdit()) {
            return back()->with('error', 'Cannot edit this campaign');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'html_content' => 'required|string',
            'plain_text_content' => 'nullable|string',
            'smtp_account_id' => 'required|exists:smtp_accounts,id',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $campaign->update([
            'smtp_account_id' => $validated['smtp_account_id'],
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'html_content' => $this->processLinks($validated['html_content']),
            'plain_text_content' => $validated['plain_text_content'] ?? strip_tags($validated['html_content']),
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'status' => $validated['scheduled_at'] ? Campaign::STATUS_SCHEDULED : Campaign::STATUS_DRAFT,
        ]);

        return redirect()->route('campaigns.show', $campaign->id)
            ->with('success', 'Campaign updated successfully');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully');
    }

    public function send(Request $request, Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        $validated = $request->validate([
            'subscribers' => 'required|array|min:1',
            'subscribers.*' => 'exists:subscribers,id',
        ]);

        $subscribers = Subscriber::whereIn('id', $validated['subscribers'])
            ->where('is_active', true)
            ->get();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'No valid subscribers found');
        }

        $campaignSubscribers = [];
        foreach ($subscribers as $subscriber) {
            $campaignSubscribers[] = [
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'status' => CampaignSubscriber::STATUS_PENDING,
                'tracking_token' => Str::random(32),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        CampaignSubscriber::insert($campaignSubscribers);

        $campaign->update([
            'total_recipients' => count($campaignSubscribers),
            'status' => Campaign::STATUS_SENDING,
            'started_at' => now(),
        ]);

        ProcessCampaignBatch::dispatch($campaign, 0);

        return redirect()->route('campaigns.show', $campaign->id)
            ->with('success', 'Campaign sending started');
    }

    public function pause(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        if (!$campaign->canPause()) {
            return back()->with('error', 'Cannot pause this campaign');
        }

        $campaign->pauseSending();

        return back()->with('success', 'Campaign paused');
    }

    public function resume(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        if ($campaign->status !== Campaign::STATUS_PAUSED) {
            return back()->with('error', 'Cannot resume this campaign');
        }

        $campaign->resumeSending();
        ProcessCampaignBatch::dispatch($campaign, $campaign->sent_count);

        return back()->with('success', 'Campaign resumed');
    }

    public function cancel(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        if (!$campaign->canCancel()) {
            return back()->with('error', 'Cannot cancel this campaign');
        }

        $campaign->cancel();

        return redirect()->route('campaigns.show', $campaign->id)
            ->with('success', 'Campaign cancelled');
    }

    public function duplicate(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        $newCampaign = $campaign->createDuplicate();

        return redirect()->route('campaigns.edit', $newCampaign->id)
            ->with('success', 'Campaign duplicated');
    }

    public function stats(Campaign $campaign)
    {
        $this->authorizeCampaign($campaign);

        $stats = [
            'total_recipients' => $campaign->total_recipients,
            'sent' => $campaign->sent_count,
            'opened' => $campaign->opened_count,
            'clicked' => $campaign->clicked_count,
            'bounced' => $campaign->bounced_count,
            'unsubscribed' => $campaign->unsubscribed_count,
            'open_rate' => $campaign->getOpenRate(),
            'click_rate' => $campaign->getClickRate(),
            'bounce_rate' => $campaign->getBounceRate(),
            'progress' => $campaign->getProgress(),
        ];

        return response()->json($stats);
    }

    private function authorizeCampaign(Campaign $campaign): void
    {
        if ($campaign->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }
    }

    private function processLinks(string $content): string
    {
        preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $content, $matches);

        $processedLinks = [];

        foreach ($matches[1] as $url) {
            if (isset($processedLinks[$url])) {
                continue;
            }

            if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                $hash = Str::random(16);
                
                CampaignLink::firstOrCreate(
                    ['hash' => $hash],
                    [
                        'url' => $url,
                        'campaign_id' => 0,
                    ]
                );

                $processedLinks[$url] = $hash;
            }
        }

        foreach ($processedLinks as $url => $hash) {
            $content = str_replace(
                $url,
                route('tracking.click', ['hash' => $hash]) . '?url=' . urlencode($url),
                $content
            );
        }

        return $content;
    }
}
