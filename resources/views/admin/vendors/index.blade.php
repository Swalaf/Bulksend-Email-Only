@extends('layouts.admin')

@section('title', 'Vendors Management')
@section('header', 'Vendors Management')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Vendor name or email..." 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="w-40">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.vendors.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Clear
            </a>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Vendors</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-store text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending Approval</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-2 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
                <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Suspended</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['suspended'] }}</p>
                </div>
                <div class="p-2 bg-red-50 dark:bg-red-900/30 rounded-lg">
                    <i class="fas fa-ban text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendors Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Business Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Listings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($vendors as $vendor)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($vendor->business_name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $vendor->business_name }}</p>
                                <p class="text-sm text-gray-500">{{ $vendor->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900 dark:text-white">{{ $vendor->business_type }}</p>
                        <p class="text-sm text-gray-500">{{ $vendor->country }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium 
                            @if($vendor->status === 'approved') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                            @elseif($vendor->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                            @elseif($vendor->status === 'rejected') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                            @else bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400
                            @endif rounded-full">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $vendor->listings_count ?? 0 }} listings
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $vendor->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.vendors.show', $vendor) }}" class="p-1 text-gray-400 hover:text-primary-600">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($vendor->status === 'pending')
                                <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1 text-gray-400 hover:text-green-600" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <button type="button" onclick="rejectVendor({{ $vendor->id }})" class="p-1 text-gray-400 hover:text-red-600" title="Reject">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            @if($vendor->status === 'approved')
                                <form action="{{ route('admin.vendors.suspend', $vendor) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-600" title="Suspend" onclick="return confirm('Suspend this vendor?')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            @endif
                            @if($vendor->status === 'suspended')
                                <form action="{{ route('admin.vendors.activate', $vendor) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1 text-gray-400 hover:text-green-600" title="Activate">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-store text-4xl mb-3"></i>
                        <p>No vendors found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $vendors->links() }}
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reject Vendor</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason for rejection</label>
                <textarea name="reason" rows="4" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg" placeholder="Provide a detailed reason..."></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function rejectVendor(id) {
    document.getElementById('rejectForm').action = '/admin/vendors/' + id + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
