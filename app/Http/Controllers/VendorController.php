<?php

namespace App\Http\Controllers;

use App\Services\MarketplaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorController extends Controller
{
    protected MarketplaceService $marketplaceService;

    public function __construct(MarketplaceService $marketplaceService)
    {
        $this->marketplaceService = $marketplaceService;
    }

    /**
     * Vendor dashboard
     */
    public function dashboard(): View
    {
        $vendor = Auth::user()->vendorProfile;
        
        if (!$vendor) {
            return view('vendor.register');
        }

        $listings = $vendor->listings()->get();
        $transactions = $vendor->transactions()->latest()->limit(10)->get();
        $purchases = $vendor->purchases()->with('user')->latest()->limit(10)->get();

        return view('vendor.dashboard', [
            'vendor' => $vendor,
            'listings' => $listings,
            'transactions' => $transactions,
            'recentPurchases' => $purchases,
        ]);
    }

    /**
     * Register as vendor
     */
    public function register(Request $request): RedirectResponse
    {
        $vendor = $this->marketplaceService->registerVendor(Auth::id(), $request->all());

        if (!$vendor) {
            return back()->withErrors($this->marketplaceService->getErrors())->withInput();
        }

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Vendor registration submitted! Pending approval.');
    }

    /**
     * Create listing
     */
    public function createListing(): View
    {
        return view('vendor.listing-create');
    }

    /**
     * Store listing
     */
    public function storeListing(Request $request): RedirectResponse
    {
        $vendor = Auth::user()->vendorProfile;
        
        if (!$vendor) {
            return redirect()->route('vendor.dashboard');
        }

        $listing = $this->marketplaceService->createListing($vendor->id, $request->all());

        if (!$listing) {
            return back()->withErrors($this->marketplaceService->getErrors())->withInput();
        }

        return redirect()->route('vendor.listings')
            ->with('success', 'Listing created! Pending admin approval.');
    }

    /**
     * Edit listing
     */
    public function editListing(int $id): View
    {
        $vendor = Auth::user()->vendorProfile;
        $listing = $vendor->listings()->findOrFail($id);

        return view('vendor.listing-edit', [
            'listing' => $listing,
        ]);
    }

    /**
     * Update listing
     */
    public function updateListing(Request $request, int $id): RedirectResponse
    {
        $vendor = Auth::user()->vendorProfile;
        $listing = $vendor->listings()->findOrFail($id);

        $listing->update($request->all());
        
        // Reset to pending if edited
        if ($listing->status === 'active') {
            $listing->update(['status' => 'pending']);
        }

        return redirect()->route('vendor.listings')
            ->with('success', 'Listing updated!');
    }

    /**
     * Delete listing
     */
    public function deleteListing(int $id): RedirectResponse
    {
        $vendor = Auth::user()->vendorProfile;
        $listing = $vendor->listings()->findOrFail($id);

        $listing->delete();

        return redirect()->route('vendor.listings')
            ->with('success', 'Listing deleted.');
    }

    /**
     * Earnings & Payouts
     */
    public function earnings(): View
    {
        $vendor = Auth::user()->vendorProfile;

        return view('vendor.earnings', [
            'vendor' => $vendor,
            'transactions' => $vendor->transactions()->latest()->paginate(20),
            'payouts' => $vendor->payouts()->latest()->paginate(20),
        ]);
    }

    /**
     * Request payout
     */
    public function requestPayout(Request $request): RedirectResponse
    {
        $vendor = Auth::user()->vendorProfile;

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = (float) $request->input('amount');

        try {
            $payout = $vendor->requestPayout($amount);
            
            return redirect()->route('vendor.earnings')
                ->with('success', 'Payout requested! Amount: $' . number_format($amount, 2));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Settings
     */
    public function settings(): View
    {
        $vendor = Auth::user()->vendorProfile;

        return view('vendor.settings', [
            'vendor' => $vendor,
        ]);
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $vendor = Auth::user()->vendorProfile;

        $vendor->update($request->all());

        return back()->with('success', 'Settings updated!');
    }
}
