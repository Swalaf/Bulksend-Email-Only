<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Campaign;
use App\Models\SmtpAccount;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\VendorProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $recentUsers = $this->getRecentUsers();
        $recentTransactions = $this->getRecentTransactions();
        
        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentTransactions'
        ));
    }

    public function stats()
    {
        return response()->json($this->getStats());
    }

    private function getStats(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalVendors = VendorProfile::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        
        $totalRevenue = Transaction::where('status', 'completed')
            ->where('type', 'payment')
            ->sum('amount');
        
        $revenueThisMonth = Transaction::where('status', 'completed')
            ->where('type', 'payment')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('amount');
        
        $totalEmails = Campaign::sum('sent_count');
        $todayEmails = Campaign::where('created_at', '>=', Carbon::now()->startOfDay())
            ->sum('sent_count');
        
        $newUsersToday = User::where('created_at', '>=', Carbon::now()->startOfDay())->count();
        
        $pendingVendors = VendorProfile::where('status', 'pending')->count();
        
        $activeCampaigns = Campaign::where('status', 'active')->count();
        
        // Get pending listings count
        $pendingListings = 0;
        if (class_exists('\App\Models\MarketplaceListing')) {
            $pendingListings = \App\Models\MarketplaceListing::where('status', 'pending')->count();
        }
        
        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'total_vendors' => $totalVendors,
            'active_subscriptions' => $activeSubscriptions,
            'total_revenue' => $totalRevenue,
            'revenue_this_month' => $revenueThisMonth,
            'total_emails_sent' => $totalEmails,
            'new_users_today' => $newUsersToday,
            'total_sent_today' => $todayEmails,
            'active_campaigns' => $activeCampaigns,
            'pending_vendors' => $pendingVendors,
            'pending_listings' => $pendingListings,
        ];
    }

    private function getRecentUsers()
    {
        return User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getRecentTransactions()
    {
        return Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    private function getRevenueData(): array
    {
        $months = [];
        $revenue = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $monthly = Transaction::where('status', 'completed')
                ->where('type', 'payment')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            
            $revenue[] = round($monthly, 2);
        }
        
        return [
            'labels' => $months,
            'data' => $revenue,
        ];
    }
}
