@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="p-3 bg-primary-50 dark:bg-primary-900/30 rounded-full">
                    <i class="fas fa-users text-primary-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    {{ $stats['new_users_today'] }}
                </span>
                <span class="text-gray-400 ml-2">new today</span>
            </div>
        </div>

        <!-- Active Campaigns -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Campaigns</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['active_campaigns']) }}</p>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-full">
                    <i class="fas fa-paper-plane text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-400">{{ number_format($stats['total_sent_today']) }} emails sent today</span>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-full">
                    <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    ${{ number_format($stats['revenue_this_month'], 2) }}
                </span>
                <span class="text-gray-400 ml-2">this month</span>
            </div>
        </div>

        <!-- Pending Vendors -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Vendors</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['pending_vendors']) }}</p>
                </div>
                <div class="p-3 bg-orange-50 dark:bg-orange-900/30 rounded-full">
                    <i class="fas fa-store text-orange-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-orange-500">{{ number_format($stats['pending_listings']) }} listings awaiting review</span>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Revenue Overview</h3>
            <div class="h-64 flex items-center justify-center text-gray-400">
                <div class="text-center">
                    <i class="fas fa-chart-line text-4xl mb-2"></i>
                    <p>Revenue chart placeholder</p>
                    <p class="text-sm">Connect Chart.js for analytics</p>
                </div>
            </div>
        </div>

        <!-- User Growth -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Growth</h3>
            <div class="h-64 flex items-center justify-center text-gray-400">
                <div class="text-center">
                    <i class="fas fa-chart-area text-4xl mb-2"></i>
                    <p>User growth chart placeholder</p>
                    <p class="text-sm">Connect Chart.js for analytics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="pb-3">User</th>
                            <th class="pb-3">Role</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentUsers as $user)
                        <tr>
                            <td class="py-3">
                                <div class="flex items-center">
                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=32" alt="">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->role ?? 'user' }}
                            </td>
                            <td class="py-3">
                                @if($user->email_verified_at)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">Verified</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full">Pending</span>
                                @endif
                            </td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
                <a href="{{ route('admin.settings.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="pb-3">User</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="py-3 text-sm text-gray-900 dark:text-white">
                                {{ $transaction->user->name ?? 'Unknown' }}
                            </td>
                            <td class="py-3 text-sm font-medium text-gray-900 dark:text-white">
                                ${{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs font-medium 
                                    @if($transaction->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                                    @elseif($transaction->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                                    @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                                    @endif rounded-full">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No transactions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i class="fas fa-user-plus text-2xl text-primary-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Add User</span>
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i class="fas fa-user-check text-2xl text-green-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Verify Users</span>
            </a>
            <a href="{{ route('admin.vendors.listings') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i class="fas fa-tags text-2xl text-orange-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Review Listings</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i class="fas fa-cog text-2xl text-gray-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Settings</span>
            </a>
        </div>
    </div>
</div>
