<?php

namespace App\Http\Controllers;

use App\Services\MarketplaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    protected MarketplaceService $marketplaceService;

    public function __construct(MarketplaceService $marketplaceService)
    {
        $this->marketplaceService = $marketplaceService;
    }

    /**
     * Browse marketplace
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->input('search'),
            'pricing_type' => $request->input('pricing_type'),
            'min_daily_limit' => $request->input('min_daily_limit'),
        ];

        $listings = $this->marketplaceService->getListings(array_filter($filters));
        $featured = $this->marketplaceService->getFeaturedListings();

        return view('marketplace.index', [
            'listings' => $listings,
            'featured' => $featured,
            'filters' => $filters,
        ]);
    }

    /**
     * View listing details
     */
    public function show(int $id): View
    {
        $listing = \App\Models\MarketplaceListing::with('vendor')
            ->findOrFail($id);

        $listing->incrementViewCount();

        $isFavorited = false;
        if (Auth::check()) {
            $isFavorited = $this->marketplaceService->isFavorited(Auth::id(), $id);
        }

        return view('marketplace.show', [
            'listing' => $listing,
            'isFavorited' => $isFavorited,
        ]);
    }

    /**
     * Toggle favorite
     */
    public function toggleFavorite(int $id): RedirectResponse
    {
        $result = $this->marketplaceService->toggleFavorite(Auth::id(), $id);

        return back()->with('success', 
            $result ? 'Added to favorites!' : 'Removed from favorites'
        );
    }

    /**
     * Purchase listing
     */
    public function purchase(Request $request, int $id): RedirectResponse
    {
        $listing = \App\Models\MarketplaceListing::findOrFail($id);

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'from_address' => 'required|email',
            'from_name' => 'nullable|string',
        ]);

        $purchase = $this->marketplaceService->processPurchase(
            Auth::id(),
            $listing,
            $credentials
        );

        if (!$purchase) {
            return back()->with('error', 'Purchase failed. Please try again.');
        }

        return redirect()->route('marketplace.purchases')
            ->with('success', 'Purchase successful! You can now use this SMTP.');
    }

    /**
     * User's purchases
     */
    public function purchases(): View
    {
        $purchases = $this->marketplaceService->getUserActivePurchases(Auth::id());
        $smtpAccounts = $this->marketplaceService->getUserMarketplaceSmtpAccounts(Auth::id());

        return view('marketplace.purchases', [
            'purchases' => $purchases,
            'smtpAccounts' => $smtpAccounts,
        ]);
    }
}
