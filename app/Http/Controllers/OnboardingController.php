<?php

namespace App\Http\Controllers;

use App\Services\OnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    protected OnboardingService $onboardingService;

    public function __construct(OnboardingService $onboardingService)
    {
        $this->onboardingService = $onboardingService;
    }

    /**
     * Show the welcome/onboarding start page.
     */
    public function welcome(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if ($user->hasCompletedOnboarding()) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.welcome', [
            'progress' => $user->getOnboardingProgress(),
        ]);
    }

    /**
     * Show the business details form.
     */
    public function business(): View|RedirectResponse
    {
        $user = Auth::user();
        $currentStep = $user->getCurrentOnboardingStep();

        // Redirect if not on business step
        if (!in_array($currentStep, ['welcome', 'business'])) {
            return redirect()->route('onboarding.welcome');
        }

        return view('onboarding.business', [
            'progress' => $user->getOnboardingProgress(),
            'business' => [
                'name' => $user->business_name,
                'description' => $user->business_description,
                'website' => $user->business_website,
            ],
        ]);
    }

    /**
     * Process business details.
     */
    public function storeBusiness(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($this->onboardingService->processBusinessDetails($user, $request->all())) {
            return redirect()->route('onboarding.smtp');
        }

        return back()->withErrors($this->onboardingService->getErrors())->withInput();
    }

    /**
     * Show the SMTP setup form.
     */
    public function smtp(): View|RedirectResponse
    {
        $user = Auth::user();
        $currentStep = $user->getCurrentOnboardingStep();

        if (!in_array($currentStep, ['business', 'smtp'])) {
            return redirect()->route('onboarding.business');
        }

        return view('onboarding.smtp', [
            'progress' => $user->getOnboardingProgress(),
            'smtpAccounts' => $user->smtpAccounts,
        ]);
    }

    /**
     * Process SMTP setup.
     */
    public function storeSmtp(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($this->onboardingService->processSmtpSetup($user, $request->all())) {
            return redirect()->route('onboarding.campaign');
        }

        return back()->withErrors($this->onboardingService->getErrors())->withInput();
    }

    /**
     * Skip SMTP setup.
     */
    public function skipSmtp(): RedirectResponse
    {
        $user = Auth::user();
        $this->onboardingService->skipSmtpSetup($user);

        return redirect()->route('onboarding.campaign');
    }

    /**
     * Show the create first campaign form.
     */
    public function campaign(): View|RedirectResponse
    {
        $user = Auth::user();
        $currentStep = $user->getCurrentOnboardingStep();

        if (!in_array($currentStep, ['smtp', 'campaign'])) {
            return redirect()->route('onboarding.smtp');
        }

        return view('onboarding.campaign', [
            'progress' => $user->getOnboardingProgress(),
            'hasSmtp' => $user->smtpAccounts()->exists(),
        ]);
    }

    /**
     * Create first campaign.
     */
    public function storeCampaign(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($this->onboardingService->createFirstCampaign($user, $request->all())) {
            return redirect()->route('onboarding.complete');
        }

        return back()->withErrors($this->onboardingService->getErrors())->withInput();
    }

    /**
     * Skip campaign creation.
     */
    public function skipCampaign(): RedirectResponse
    {
        $user = Auth::user();
        $this->onboardingService->skipCampaignCreation($user);

        return redirect()->route('onboarding.complete');
    }

    /**
     * Show the completion screen.
     */
    public function complete(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user->hasCompletedOnboarding()) {
            return redirect()->route('onboarding.welcome');
        }

        return view('onboarding.complete', [
            'user' => $user,
        ]);
    }

    /**
     * Get onboarding progress (AJAX).
     */
    public function progress(Request $request)
    {
        $user = $request->user();
        
        return response()->json($this->onboardingService->getOnboardingData($user));
    }
}
