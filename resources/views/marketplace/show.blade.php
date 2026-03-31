<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $listing->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $listing->name }}</h1>
                            <p class="text-gray-600 mt-1">by {{ $listing->vendor->shop_name }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-indigo-600">{{ $listing->getFormattedPrice() }}</div>
                            <p class="text-sm text-gray-500">per {{ $listing->pricing_type === 'subscription' ? 'month' : 'email' }}</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">Active Listing</span>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $listing->description }}</p>
                    </div>

                    <!-- Features -->
                    @if($listing->features)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Features</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($listing->features as $feature)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-gray-700">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- SMTP Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">SMTP Configuration</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Host:</span>
                                    <span class="ml-2 text-gray-900">{{ $listing->host }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Port:</span>
                                    <span class="ml-2 text-gray-900">{{ $listing->port }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Encryption:</span>
                                    <span class="ml-2 text-gray-900">{{ strtoupper($listing->encryption) }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">From Address:</span>
                                    <span class="ml-2 text-gray-900">{{ $listing->from_address }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Limits -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Sending Limits</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Daily Limit</p>
                                        <p class="text-2xl font-bold text-blue-900">{{ number_format($listing->daily_limit) }}</p>
                                        <p class="text-xs text-blue-600">emails per day</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m0 0v10a2 2 0 01-2 2H8a2 2 0 01-2-2V7z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-green-800">Monthly Limit</p>
                                        <p class="text-2xl font-bold text-green-900">{{ number_format($listing->monthly_limit) }}</p>
                                        <p class="text-xs text-green-600">emails per month</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Listing Stats</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-2xl font-bold text-gray-900">{{ $listing->view_count }}</p>
                                <p class="text-sm text-gray-600">Views</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-2xl font-bold text-gray-900">{{ $listing->purchase_count }}</p>
                                <p class="text-sm text-gray-600">Purchases</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-2xl font-bold text-gray-900">{{ $listing->vendor->is_verified ? 'Verified' : 'Unverified' }}</p>
                                <p class="text-sm text-gray-600">Vendor Status</p>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Button -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            Secure purchase • Instant delivery • 24/7 support
                        </div>
                        <a href="{{ route('marketplace.purchase', $listing->id) }}"
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700">
                            Purchase Now
                            <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.5.728 1.5h9.836c.912 0 1.358-.87.728-1.5L13 7m5 4v1m0-1V7m-5 8h.01M11 11h1v1h-1z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>