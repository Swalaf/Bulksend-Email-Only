@extends('layouts.admin')

@section('title', 'Vendor Management')
@section('header', 'Vendor Management')

@section('content')
<div class="space-y-6">
    <!-- Vendors Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Shop</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Listings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Earnings</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse(\App\Models\VendorProfile::with('user')->get() as $vendor)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($vendor->user->name) }}&size=40" alt="">
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $vendor->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $vendor->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                        {{ $vendor->shop_name }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($vendor->status === 'active') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                            @elseif($vendor->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                            @elseif($vendor->status === 'suspended') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                            @else bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400 @endif">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                        {{ $vendor->listings()->count() }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                        ${{ number_format($vendor->total_earnings, 2) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            @if($vendor->status === 'pending')
                                <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1 text-green-600 hover:text-green-900" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            @if($vendor->status === 'active')
                                <form action="{{ route('admin.vendors.suspend', $vendor) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1 text-red-600 hover:text-red-900" title="Suspend">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            @elseif($vendor->status === 'suspended')
                                <form action="{{ route('admin.vendors.activate', $vendor) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1 text-green-600 hover:text-green-900" title="Activate">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-users text-4xl mb-3"></i>
                        <p>No vendors found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
