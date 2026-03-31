<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Your SMTP Listings</h3>
                        <a href="{{ route('vendor.listing-create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-indigo-700">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Listing
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pricing</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse(Auth::user()->vendorProfile->listings ?? [] as $listing)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $listing->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $listing->host }}:{{ $listing->port }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $listing->getFormattedPrice() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($listing->status === 'active') bg-green-100 text-green-800
                                                @elseif($listing->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($listing->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($listing->status) }}
                                            </span>
                                            @if($listing->status === 'rejected' && $listing->rejection_reason)
                                                <p class="text-xs text-red-600 mt-1">{{ $listing->rejection_reason }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $listing->purchase_count }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $listing->view_count }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            <a href="{{ route('marketplace.show', $listing->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('vendor.listing-edit', $listing) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form method="POST" action="{{ route('vendor.listing-delete', $listing) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            <p class="mt-2 text-lg">No listings yet</p>
                                            <p class="text-sm">Create your first SMTP listing to start earning</p>
                                            <a href="{{ route('vendor.listing-create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-indigo-700">
                                                Create Your First Listing
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>