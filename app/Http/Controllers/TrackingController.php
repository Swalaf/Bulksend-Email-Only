<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignLink;
use App\Models\CampaignLinkClick;
use App\Models\CampaignSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    public function open(Request $request)
    {
        $token = $request->get('token');
        $campaignId = $request->get('campaign_id');

        if (!$token || !$campaignId) {
            return response()->view('tracking.pixel', ['pixel' => base64_encode('')])->header('Content-Type', 'image/gif');
        }

        $subscriber = CampaignSubscriber::where('tracking_token', $token)
            ->where('campaign_id', $campaignId)
            ->first();

        if (!$subscriber) {
            return $this->renderPixel();
        }

        if ($subscriber->status !== CampaignSubscriber::STATUS_OPENED) {
            $subscriber->markAsOpened();
        }

        return $this->renderPixel();
    }

    public function click(Request $request, string $hash)
    {
        $link = CampaignLink::where('hash', $hash)->first();
        
        if (!$link) {
            abort(404);
        }

        $subscriberId = null;
        $campaignSubscriberId = null;

        if ($request->has('token') && $request->has('campaign_id')) {
            $campaignSubscriber = CampaignSubscriber::where('tracking_token', $request->get('token'))
                ->where('campaign_id', $request->get('campaign_id'))
                ->first();

            if ($campaignSubscriber) {
                $subscriberId = $campaignSubscriber->subscriber_id;
                $campaignSubscriberId = $campaignSubscriber->id;

                if ($campaignSubscriber->status !== CampaignSubscriber::STATUS_CLICKED) {
                    $campaignSubscriber->markAsClicked();
                }
            }
        }

        CampaignLinkClick::create([
            'campaign_link_id' => $link->id,
            'campaign_subscriber_id' => $campaignSubscriberId,
            'subscriber_id' => $subscriberId,
            'campaign_id' => $link->campaign_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'clicked_at' => now(),
        ]);

        $link->incrementClick($subscriberId !== null);

        return redirect()->to($link->url);
    }

    public function bounce(Request $request)
    {
        $messageId = $request->get('message_id');
        
        if (!$messageId) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $log = \App\Models\CampaignSendLog::where('message_id', $messageId)->first();
        
        if ($log && $log->status !== \App\Models\CampaignSendLog::STATUS_BOUNCED) {
            $log->markAsBounced();
            
            $subscriber = $log->campaignSubscriber;
            if ($subscriber) {
                $subscriber->markAsBounced();
            }
        }

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $token = $request->get('token');
        $campaignId = $request->get('campaign_id');

        if (!$token || !$campaignId) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $subscriber = CampaignSubscriber::where('tracking_token', $token)
            ->where('campaign_id', $campaignId)
            ->first();

        if ($subscriber) {
            $subscriber->markAsUnsubscribed();

            $subscriberModel = $subscriber->subscriber;
            if ($subscriberModel) {
                $subscriberModel->update(['is_active' => false]);
            }
        }

        return response()->json(['success' => true]);
    }

    private function renderPixel()
    {
        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        return response($gif)->header('Content-Type', 'image/gif');
    }
}
