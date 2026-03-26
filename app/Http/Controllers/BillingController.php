<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Invoice;
use App\Services\BillingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function __construct(
        private BillingService $billingService,
        private PaymentService $paymentService
    ) {}

    public function index()
    {
        $user = auth()->user();
        $subscription = $this->billingService->getSubscription($user);
        $usageStats = $this->billingService->getUsageStats($user);
        $invoices = Invoice::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('billing.index', compact('subscription', 'usageStats', 'invoices'));
    }

    public function plans()
    {
        $plans = Plan::active()->ordered()->get();
        
        return view('billing.plans', compact('plans'));
    }

    public function checkout(Request $request)
    {
        $plan = Plan::findOrFail($request->get('plan_id'));
        
        return view('billing.checkout', compact('plan'));
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method_id' => 'required|string',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $user = auth()->user();

        // Create subscription via payment service
        $result = $this->paymentService->createSubscription(
            $user,
            $plan,
            $validated['payment_method_id']
        );

        // Create local subscription
        $subscription = $this->billingService->createSubscription($user, $plan);

        return redirect()->route('billing.index')
            ->with('success', "Subscribed to {$plan->name} plan");
    }

    public function changePlan(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $user = auth()->user();

        $this->billingService->changePlan($user, $plan);

        return redirect()->route('billing.index')
            ->with('success', "Plan changed to {$plan->name}");
    }

    public function cancel(Request $request)
    {
        $user = auth()->user();
        
        $this->billingService->cancelSubscription($user);

        return redirect()->route('billing.index')
            ->with('success', 'Subscription cancelled');
    }

    public function invoices()
    {
        $invoices = Invoice::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('billing.invoices', compact('invoices'));
    }

    public function invoice(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return view('billing.invoice', compact('invoice'));
    }

    public function credits()
    {
        $balance = $this->billingService->getCreditBalance(auth()->user());

        return view('billing.credits', compact('balance'));
    }

    public function purchaseCredits(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:100', // Minimum 100 credits
            'payment_method_id' => 'required|string',
        ]);

        $user = auth()->user();
        
        // Calculate price (e.g., $10 per 1000 credits)
        $price = ($validated['amount'] / 1000) * 10;

        // Create payment intent
        $intent = $this->paymentService->createPaymentIntent($user, $price);

        // Process payment (simplified)
        $this->paymentService->handleSuccessfulPayment($user, [
            'payment_id' => $intent['payment_intent_id'],
            'amount' => $price,
            'credits' => $validated['amount'],
            'description' => "Purchased {$validated['amount']} credits",
        ]);

        return redirect()->route('billing.credits')
            ->with('success', "Purchased {$validated['amount']} credits");
    }
}
