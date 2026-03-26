<?php

namespace App\Services;

use App\Models\VendorProfile;
use App\Models\MarketplaceListing;
use App\Models\MarketplacePurchase;
use App\Models\MarketplaceSmtpAccount;
use App\Models\MarketplaceTransaction;
use Illuminate\Support\Facades\Validator;
use Exception;

class MarketplaceService extends BaseService
{
    protected float $defaultCommissionRate = 10.00; // 10%

    /**
     * Become a vendor
     */
    public function registerVendor(int $userId, array $data): ?VendorProfile
    {
        $rules = [
            'shop_name' => 'required|string|max:255|unique:vendor_profiles,shop_name',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
        ];

        if (!$this->validate($data, $rules)) {
            return null;
        }

        return VendorProfile::create([
            'user_id' => $userId,
            'shop_name' => $data['shop_name'],
            'description' => $data['description'] ?? null,
            'website' => $data['website'] ?? null,
            'status' => 'pending',
            'commission_rate' => $this->defaultCommissionRate,
        ]);
    }

    /**
     * Create marketplace listing
     */
    public function createListing(int $vendorId, array $data): ?MarketplaceListing
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'encryption' => 'required|in:tls,ssl,none',
            'from_address' => 'required|email',
            'from_name' => 'nullable|string|max:255',
            'pricing_type' => 'required|in:per_email,subscription',
            'price_per_email' => 'nullable|numeric|min:0',
            'monthly_subscription' => 'nullable|numeric|min:0',
            'free_emails' => 'nullable|integer|min:0',
            'included_emails' => 'nullable|integer|min:1',
            'daily_limit' => 'required|integer|min:1',
            'monthly_limit' => 'required|integer|min:1',
            'features' => 'nullable|array',
        ];

        // Conditional validation
        if ($data['pricing_type'] === 'per_email') {
            $rules['price_per_email'] = 'required|numeric|min:0.0001';
        } else {
            $rules['monthly_subscription'] = 'required|numeric|min:1';
            $rules['included_emails'] = 'required|integer|min:1';
        }

        if (!$this->validate($data, $rules)) {
            return null;
        }

        return MarketplaceListing::create([
            'vendor_id' => $vendorId,
            'name' => $data['name'],
            'description' => $data['description'],
            'host' => $data['host'],
            'port' => $data['port'],
            'encryption' => $data['encryption'],
            'from_address' => $data['from_address'],
            'from_name' => $data['from_name'],
            'pricing_type' => $data['pricing_type'],
            'price_per_email' => $data['price_per_email'] ?? null,
            'monthly_subscription' => $data['monthly_subscription'] ?? null,
            'free_emails' => $data['free_emails'] ?? 0,
            'included_emails' => $data['included_emails'] ?? null,
            'daily_limit' => $data['daily_limit'],
            'monthly_limit' => $data['monthly_limit'],
            'features' => $data['features'] ?? null,
            'status' => 'pending', // Requires admin approval
        ]);
    }

    /**
     * Process purchase
     */
    public function processPurchase(
        int $userId,
        MarketplaceListing $listing,
        array $credentials,
        ?string $paymentIntentId = null
    ): ?MarketplacePurchase {
        $vendor = $listing->vendor;
        $commissionRate = (float) $vendor->commission_rate;

        // Calculate amounts
        if ($listing->pricing_type === 'subscription') {
            $amount = (float) $listing->monthly_subscription;
            $emailsCredit = $listing->included_emails;
            $isSubscription = true;
        } else {
            // Per-email purchase
            $emailsCredit = $listing->free_emails > 0 ? $listing->free_emails : 1000;
            $amount = $emailsCredit * (float) $listing->price_per_email;
            $isSubscription = false;
        }

        $commissionAmount = $amount * ($commissionRate / 100);
        $vendorAmount = $amount - $commissionAmount;

        // Create purchase
        $purchase = MarketplacePurchase::create([
            'user_id' => $userId,
            'listing_id' => $listing->id,
            'vendor_id' => $listing->vendor_id,
            'type' => $isSubscription ? 'subscription' : 'one_time',
            'amount' => $amount,
            'vendor_amount' => $vendorAmount,
            'commission_amount' => $commissionAmount,
            'commission_rate' => $commissionRate,
            'emails_credit' => $emailsCredit,
            'emails_used' => 0,
            'is_subscription' => $isSubscription,
            'subscription_active' => $isSubscription,
            'subscription_start' => $isSubscription ? now() : null,
            'subscription_end' => $isSubscription ? now()->addMonth() : null,
            'stripe_payment_id' => $paymentIntentId,
            'payment_status' => 'completed',
            'is_active' => true,
            'purchased_at' => now(),
        ]);

        // Create SMTP account
        $this->createSmtpAccount($purchase, $listing, $credentials);

        // Record transaction
        $this->recordTransaction($purchase, $vendorAmount, $commissionAmount);

        // Update listing purchase count
        $listing->incrementPurchaseCount();

        // Add vendor earnings
        $vendor->addEarnings($vendorAmount);

        return $purchase;
    }

    /**
     * Create SMTP account from purchase
     */
    protected function createSmtpAccount(
        MarketplacePurchase $purchase,
        MarketplaceListing $listing,
        array $credentials
    ): MarketplaceSmtpAccount {
        return MarketplaceSmtpAccount::create([
            'user_id' => $purchase->user_id,
            'purchase_id' => $purchase->id,
            'listing_id' => $listing->id,
            'name' => $listing->name,
            'host' => $listing->host,
            'port' => $listing->port,
            'encryption' => $listing->encryption,
            'from_address' => $credentials['from_address'] ?? $listing->from_address,
            'from_name' => $credentials['from_name'] ?? $listing->from_name,
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'daily_limit' => $listing->daily_limit,
            'monthly_limit' => $listing->monthly_limit,
            'is_active' => true,
        ]);
    }

    /**
     * Record transaction
     */
    protected function recordTransaction(
        MarketplacePurchase $purchase,
        float $vendorAmount,
        float $commissionAmount
    ): void {
        MarketplaceTransaction::create([
            'vendor_id' => $purchase->vendor_id,
            'purchase_id' => $purchase->id,
            'type' => 'sale',
            'amount' => $purchase->amount,
            'commission_amount' => $commissionAmount,
            'net_amount' => $vendorAmount,
            'status' => 'completed',
            'description' => 'Purchase of ' . $purchase->listing->name,
        ]);
    }

    /**
     * Use email credit
     */
    public function useEmailCredit(MarketplacePurchase $purchase): bool
    {
        if (!$purchase->hasCreditsLeft()) {
            return false;
        }

        $purchase->useCredit();
        return true;
    }

    /**
     * Get active purchases for user
     */
    public function getUserActivePurchases(int $userId)
    {
        return MarketplacePurchase::where('user_id', $userId)
            ->with(['listing', 'vendor'])
            ->active()
            ->withCredits()
            ->get();
    }

    /**
     * Get marketplace SMTP accounts for user
     */
    public function getUserMarketplaceSmtpAccounts(int $userId)
    {
        return MarketplaceSmtpAccount::where('user_id', $userId)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Browse marketplace listings
     */
    public function getListings(array $filters = [])
    {
        $query = MarketplaceListing::active()
            ->with('vendor')
            ->withCount('favorites');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['pricing_type'])) {
            $query->where('pricing_type', $filters['pricing_type']);
        }

        if (!empty($filters['min_daily_limit'])) {
            $query->where('daily_limit', '>=', $filters['min_daily_limit']);
        }

        return $query->orderBy('purchase_count', 'desc')->paginate(12);
    }

    /**
     * Get featured listings
     */
    public function getFeaturedListings()
    {
        return MarketplaceListing::active()
            ->with('vendor')
            ->orderBy('purchase_count', 'desc')
            ->limit(6)
            ->get();
    }

    /**
     * Approve listing
     */
    public function approveListing(MarketplaceListing $listing): bool
    {
        $listing->update([
            'status' => 'active',
            'rejection_reason' => null,
        ]);
        return true;
    }

    /**
     * Reject listing
     */
    public function rejectListing(MarketplaceListing $listing, string $reason): bool
    {
        $listing->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
        return true;
    }

    /**
     * Toggle favorite
     */
    public function toggleFavorite(int $userId, int $listingId): bool
    {
        $favorite = \App\Models\MarketplaceFavorite::where('user_id', $userId)
            ->where('listing_id', $listingId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return false; // Removed
        }

        \App\Models\MarketplaceFavorite::create([
            'user_id' => $userId,
            'listing_id' => $listingId,
        ]);
        return true; // Added
    }

    /**
     * Check if user favorited listing
     */
    public function isFavorited(int $userId, int $listingId): bool
    {
        return \App\Models\MarketplaceFavorite::where('user_id', $userId)
            ->where('listing_id', $listingId)
            ->exists();
    }
}
