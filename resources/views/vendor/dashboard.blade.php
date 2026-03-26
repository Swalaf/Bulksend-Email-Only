<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Vendor Dashboard') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('marketplace.index') }}" target="_blank" class="text-indigo-600 hover:text-indigo-700">
                    View Marketplace
                </a>
                <a href="{{ route('vendor.listing-create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Listing
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Alert -->
            @if($vendor->status === 'pending')
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="font-medium text-yellow-800">Your vendor account is pending approval</p>
                        <p class="text-sm text-yellow-700">Once approved, your listings will appear in the marketplace.</p>
                    </div>
                </div>
            @elseif($vendor->status === 'suspended')
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="font-medium text-red-800">Your vendor account has been suspended</p>
                        <p class="text-sm text-red-700">Please contact support for more information.</p>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Pending Earnings -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending Earnings</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($vendor->pending_earnings, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Earnings -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Earnings</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($vendor->total_earnings, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Active Listings -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Active Listings</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $listings->where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Sales -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Sales</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $vendor->getTotalSales() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Listings -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900">My Listings</h3>
                        </div>
                        
                        @if($listings->isEmpty())
                            <div class="p-6 text-center">
                                <p class="text-gray-500 mb-4">No listings yet. Create your first one!</p>
                                <a href="{{ route('vendor.listing-create') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                                    Create Listing →
                                </a>
                            </div>
                        @else
                            <div class="divide-y divide-gray-200">
                                @foreach($listings as $listing)
                                    <div class="p-6 flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $listing->name }}</h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $listing->getFormattedPrice() }} • 
                                                <span class="{{ $listing->status === 'active' ? 'text-green-600' : 'text-yellow-600' }}">
                                                    {{ ucfirst($listing->status) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-500">
                                                {{ $listing->purchase_count }} sales
                                            </span>
                                            <a href="{{ route('vendor.listing-edit', $listing->id) }}" 
                                               class="p-2 text-gray-400 hover:text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900">Recent Transactions</h3>
                        </div>
                        
                        @if($transactions->isEmpty())
                            <div class="p-6 text-center">
                                <p class="text-gray-500">No transactions yet</p>
                            </div>
                        @else
                            <div class="divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">${{ number_format($transaction->net_amount, 2) }}</p>
                                                <p class="text-xs text-gray-500">{{ $transaction->description }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-medium rounded 
                                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">{{ $transaction->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
