@extends('layouts.admin')

@section('title', 'Users Management')
@section('header', 'Users Management')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="w-40">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="vendor" {{ request('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                </select>
            </div>
            <div class="w-40">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                    <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Clear
            </a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=40" alt="">
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium 
                            @if($user->hasRole('admin')) bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400
                            @elseif($user->hasRole('vendor')) bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400
                            @else bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                            @endif rounded-full">
                            {{ ucfirst($user->role ?? 'user') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_banned)
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full">Banned</span>
                        @elseif($user->email_verified_at)
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full">Verified</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="p-1 text-gray-400 hover:text-primary-600">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$user->email_verified_at)
                                <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1 text-gray-400 hover:text-green-600" title="Verify">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                            @endif
                            @if($user->is_banned)
                                <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1 text-gray-400 hover:text-green-600" title="Unban">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-600" title="Ban" onclick="return confirm('Are you sure you want to ban this user?')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-users text-4xl mb-3"></i>
                        <p>No users found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $users->links() }}
    </div>
</div>
