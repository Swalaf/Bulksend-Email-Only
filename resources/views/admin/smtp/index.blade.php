@extends('layouts.admin')

@section('title', 'SMTP Accounts')
@section('header', 'SMTP Accounts')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Email or host..." 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="w-40">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.smtp.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Clear
            </a>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total SMTP</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-server text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Verified</p>
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['verified'] }}</p>
                </div>
                <div class="p-2 bg-primary-50 dark:bg-primary-900/30 rounded-lg">
                    <i class="fas fa-shield-alt text-primary-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Emails Sent Today</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['sent_today']) }}</p>
                </div>
                <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <i class="fas fa-paper-plane text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- SMTP Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SMTP Account</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Provider</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Verified</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Daily Limit</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($smtpAccounts as $smtp)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                <i class="fas fa-envelope text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $smtp->from_email }}</p>
                                <p class="text-xs text-gray-500">{{ $smtp->host }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $smtp->user->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $smtp->provider ?? 'Custom' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium 
                            @if($smtp->is_active) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                            @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                            @endif rounded-full">
                            {{ $smtp->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($smtp->is_verified)
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">
                                <i class="fas fa-check mr-1"></i>Verified
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full">
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ number_format($smtp->daily_limit) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.smtp.show', $smtp) }}" class="p-1 text-gray-400 hover:text-primary-600">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.smtp.toggle', $smtp) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1 text-gray-400 hover:text-{{ $smtp->is_active ? 'red' : 'green' }}-600" title="{{ $smtp->is_active ? 'Disable' : 'Enable' }}">
                                    <i class="fas fa-{{ $smtp->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            @if(!$smtp->is_verified)
                                <form action="{{ route('admin.smtp.verify', $smtp) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1 text-gray-400 hover:text-green-600" title="Verify">
                                        <i class="fas fa-shield-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-server text-4xl mb-3"></i>
                        <p>No SMTP accounts found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $smtpAccounts->links() }}
    </div>
</div>
