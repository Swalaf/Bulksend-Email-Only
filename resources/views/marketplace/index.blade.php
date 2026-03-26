<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('SMTP Marketplace') }}
            </h2>
            <a href="{{ route('marketplace.purchases') }}" class="text-indigo-600 hover:text-indigo-700">
                My Purchases
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Featured Section -->
            @if($featured->count() > 0)
                <div class="mb-12">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Featured SMTP Services</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featured as $listing)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $listing->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $listing->vendor->shop_name }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Active</span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $listing->description }}</p>
                                    
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-lg font-bold text-indigo-600">{{ $listing->getFormattedPrice() }}</span>
                                        </div>
                                        <a href="{{ route('marketplace.show', $listing->id) }}" 
                                           class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                            View Details
                                        </a>
                                    </div>
                                    
                                    <div class="mt-4 flex items-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ $listing->view_count }} views
                                        <span class="mx-2">•</span>
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.5.728 1.5h9.836c.912 0 1.358-.87.728-1.5L13 7m5 4v1m0-1V7m-5 8h.01M11 11h1v1h-1z" />
                                        </svg>
                                        {{ $listing->purchase_count }} purchases
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <form action="{{ route('marketplace.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" 
                               name="search" 
                               value="{{ $filters['search'] ?? '' }}"
                               placeholder="Search SMTP services..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-48">
                        <select name="pricing_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Pricing</option>
                            <option value="per_email" {{ ($filters['pricing_type'] ?? '') === 'per_email' ? 'selected' : '' }}>Per Email</option>
                            <option value="subscription" {{ ($filters['pricing_type'] ?? '') === 'subscription' ? 'selected' : '' }}>Subscription</option>
                        </select>
                    </div>
                    <div class="w-48">
                        <select name="min_daily_limit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Any Limit</option>
                            <option value="500" {{ ($filters['min_daily_limit'] ?? '') == '500' ? 'selected' : '' }}>500+/day</option>
                            <option value="1000" {{ ($filters['min_daily_limit'] ?? '') == '1000' ? 'selected' : '' }}>1000+/day</option>
                            <option value="5000" {{ ($filters['min_daily_limit'] ?? '') == '5000' ? 'selected' : '' }}>5000+/day</option>
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-700">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Listings Grid -->
            @if($listings->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No listings found</h3>
                    <p class="text-gray-500">Try adjusting your filters or check back later.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($listings as $listing)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900 truncate">{{ $listing->name }}</h4>
                                    @if($listing->vendor->is_verified)
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                                
                                <p class="text-xs text-gray-500 mb-3">{{ $listing->vendor->shop_name }}</p>
                                
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $listing->description }}</p>
                                
                                <!-- Features -->
                                @if($listing->features)
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @foreach(array_slice($listing->features, 0, 3) as $feature)
                                            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">{{ $feature }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                    <div>
                                        <span class="text-lg font-bold text-indigo-600">{{ $listing->getFormattedPrice() }}</span>
                                        <p class="text-xs text-gray-500">{{ number_format($listing->daily_limit) }}/day</p>
                                    </div>
                                    <a href="{{ route('marketplace.show', $listing->id) }}" 
                                       class="px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
