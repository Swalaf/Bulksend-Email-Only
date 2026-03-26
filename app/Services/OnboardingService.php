<?php

namespace App\Services;

use App\Models\User;
use App\Models\SmtpAccount;
use App\Models\Campaign;
use App\Models\SubscriberList;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OnboardingService extends BaseService
{
    public function processBusinessDetails(User $user, array $data): bool
    {
        $rules = [
            'business_name' => 'required|string|max:255',
            'business_description' => 'nullable|string|max:1000',
            'business_website' => 'nullable|url|max:255',
        ];

        if (!$this->validate($data, $rules)) {
            return false;
        }

        $user->update([
            'business_name' => $data['business_name'],
            'business_description' => $data['business_description'] ?? null,
            'business_website' => $data['business_website'] ?? null,
            'onboarding_step' => 'smtp',
        ]);

        return true;
    }

    public function processSmtpSetup(User $user, array $data): bool
    {
        $rules = [
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|in:25,465,587',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'encryption' => 'nullable|in:tls,ssl',
            'from_address' => 'required|email',
            'from_name' => 'nullable|string|max:255',
        ];

        if (!$this->validate($data, $rules)) {
            return false;
        }

        SmtpAccount::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['password'],
            'encryption' => $data['encryption'] ?? 'tls',
            'from_address' => $data['from_address'],
            'from_name' => $data['from_name'] ?? $user->business_name,
            'is_default' => true,
            'is_active' => true,
        ]);

        $user->update(['onboarding_step' => 'campaign']);

        return true;
    }

    public function skipSmtpSetup(User $user): bool
    {
        $user->update(['onboarding_step' => 'campaign']);
        return true;
    }

    public function createFirstCampaign(User $user, array $data): bool
    {
        $rules = [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'nullable|string',
        ];

        if (!$this->validate($data, $rules)) {
            return false;
        }

        $defaultSmtp = $user->smtpAccounts()->where('is_default', true)->first();

        Campaign::create([
            'user_id' => $user->id,
            'smtp_account_id' => $defaultSmtp?->id,
            'name' => $data['name'],
            'subject' => $data['subject'],
            'content' => $data['content'] ?? '',
            'html_content' => $data['content'] ?? '',
            'status' => 'draft',
        ]);

        $user->update(['onboarding_step' => 'complete']);

        return true;
    }

    public function skipCampaignCreation(User $user): bool
    {
        $user->update(['onboarding_step' => 'complete']);
        return true;
    }

    public function getOnboardingData(User $user): array
    {
        return [
            'current_step' => $user->getCurrentOnboardingStep(),
            'progress' => $user->getOnboardingProgress(),
            'business_name' => $user->business_name,
            'has_smtp' => $user->smtpAccounts()->exists(),
            'has_campaign' => $user->campaigns()->exists(),
            'steps' => [
                'welcome' => [
                    'title' => 'Welcome',
                    'description' => 'Get started with BulkSend',
                    'completed' => in_array($user->getCurrentOnboardingStep(), ['business', 'smtp', 'campaign', 'complete']),
                ],
                'business' => [
                    'title' => 'Business Details',
                    'description' => 'Tell us about your business',
                    'completed' => in_array($user->getCurrentOnboardingStep(), ['smtp', 'campaign', 'complete']),
                ],
                'smtp' => [
                    'title' => 'SMTP Setup',
                    'description' => 'Configure your email sender',
                    'completed' => in_array($user->getCurrentOnboardingStep(), ['campaign', 'complete']),
                ],
                'campaign' => [
                    'title' => 'Create Campaign',
                    'description' => 'Send your first email',
                    'completed' => $user->getCurrentOnboardingStep() === 'complete',
                ],
            ],
        ];
    }
}
