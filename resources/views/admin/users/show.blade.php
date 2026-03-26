@extends('layouts.admin')

@section('title', 'User Details')
@section('header', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-gray-600 hover:text-primary-600">
        <i class="fas fa-arrow-left mr-2"></i> Back to Users
    </a>

    <!-- User Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-16 w-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=64" alt="">
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if(!$user->email_verified_at)
                        <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-check-circle mr-2"></i>Verify
                            </button>
                        </form>
                    @endif
                    @if($user->is_banned)
                        <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-unlock mr-2"></i>Unban
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you sure you want to ban this user?')">
                                <i class="fas fa-ban mr-2"></i>Ban
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Details</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Role</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            <span class="px-2 py-1 text-xs font-medium 
                                @if($user->hasRole('admin')) bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400
                                @elseif($user->hasRole('vendor')) bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400
                                @else bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                @endif rounded-full">
                                {{ ucfirst($user->role ?? 'user') }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            @if($user->is_banned)
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full">Banned</span>
                            @elseif($user->email_verified_at)
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full">Pending Verification</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Email Verified</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : 'Not verified' }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            {{ $user->created_at->format('M d, Y H:i') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Last Updated</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            {{ $user->updated_at->format('M d, Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Stats -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity Stats</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Total Campaigns</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ number_format($user->campaigns()->count()) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Active Campaigns</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ number_format($user->campaigns()->where('status', 'active')->count()) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Total Subscribers</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ number_format($user->subscribers()->count()) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">SMTP Accounts</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ number_format($user->smtpAccounts()->count()) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Campaigns</h3>
        </div>
        <div class="p-6">
            @if($user->campaigns()->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="pb-3">Campaign</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Recipients</th>
                            <th class="pb-3">Sent</th>
                            <th class="pb-3">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($user->campaigns()->latest()->take(5)->get() as $campaign)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $campaign->name }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs font-medium 
                                    @if($campaign->status === 'sent') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                                    @elseif($campaign->status === 'draft') bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400
                                    @else bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                    @endif rounded-full">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ number_format($campaign->recipients_count ?? 0) }}</td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ number_format($campaign->sent_count ?? 0) }}</td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ $campaign->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No campaigns yet</p>
            @endif
        </div>
    </div>
</div>
