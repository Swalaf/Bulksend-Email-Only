<?php

namespace App\Http\Middleware;

use App\Services\BillingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function __construct(
        private BillingService $billingService
    ) {}

    public function handle(Request $request, Closure $next, string $feature = null): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has active subscription
        $subscription = $this->billingService->getSubscription($user);
        
        if (!$subscription) {
            // Redirect to subscription page for paid features
            if ($feature) {
                return redirect()->route('billing.plans')
                    ->with('error', 'Please subscribe to access this feature');
            }
            
            return redirect()->route('billing.plans')
                ->with('error', 'Please subscribe to continue');
        }

        // Check specific feature access
        if ($feature) {
            $canUse = $this->billingService->canPerform($user, $feature);
            
            if (!$canUse['allowed']) {
                return redirect()->route('billing.plans')
                    ->with('error', $canUse['reason'] ?? 'Upgrade your plan to access this feature');
            }
        }

        return $next($request);
    }
}
