<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8 animate-slide-up">
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-primary-900 mb-2">
                        SMTP Marketplace
                    </h1>
                    <p class="text-primary-600 text-lg">
                        Discover premium SMTP services from verified vendors
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-accent-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-shopping-cart text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Section -->
    @if($featured->count() > 0)
        <div id="featured" class="mb-12 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl p-3 mr-4">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-primary-900">Featured Services</h2>
                        <p class="text-primary-600">Premium SMTP solutions from trusted vendors</p>
                    </div>
                </div>
                <a href="#all-services" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featured as $listing)
                    <div class="group relative glass-effect rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-white/20">
                        <!-- Gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-accent-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <div class="relative p-8">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-primary-900 mb-1 group-hover:text-primary-700 transition-colors">{{ $listing->name }}</h3>
                                    <div class="flex items-center">
                                        <p class="text-sm text-primary-600">{{ $listing->vendor->shop_name }}</p>
                                        @if($listing->vendor->is_verified)
                                            <svg class="w-4 h-4 text-blue-500 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Featured</div>
                            </div>

                            <p class="text-primary-600 mb-6 line-clamp-3 leading-relaxed">{{ $listing->description }}</p>

                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <div class="text-2xl font-bold text-primary-600 mb-1">{{ $listing->getFormattedPrice() }}</div>
                                    <div class="text-sm text-primary-500">{{ number_format($listing->daily_limit) }}/day limit</div>
                                </div>
                                <a href="{{ route('marketplace.show', $listing->id) }}"
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-xl hover:from-accent-600 hover:to-primary-600 transition-all duration-300 transform hover:scale-105">
                                    View Details
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                            <div class="flex items-center justify-between text-sm text-primary-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ number_format($listing->view_count) }} views
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    {{ number_format($listing->purchase_count) }} sold
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters Section -->
    <div id="all-services" class="glass-effect rounded-2xl p-8 mb-8 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-primary-900">Filter & Search</h3>
            <div class="text-sm text-primary-600">
                <i class="fas fa-filter mr-1"></i>Find your perfect SMTP service
            </div>
        </div>

        <form id="filter-form" action="{{ route('marketplace.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-semibold text-primary-700 mb-3">Search Services</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Search by name, vendor..."
                           class="w-full pl-10 pr-4 py-3 bg-white/60 border border-primary-200 rounded-xl text-primary-900 placeholder-primary-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                </div>
            </div>

            <div>
                <label for="pricing_type" class="block text-sm font-semibold text-primary-700 mb-3">Pricing Model</label>
                <select name="pricing_type" class="w-full px-4 py-3 bg-white/60 border border-primary-200 rounded-xl text-primary-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    <option value="">All Models</option>
                    <option value="per_email" {{ ($filters['pricing_type'] ?? '') === 'per_email' ? 'selected' : '' }}>Per Email</option>
                    <option value="subscription" {{ ($filters['pricing_type'] ?? '') === 'subscription' ? 'selected' : '' }}>Subscription</option>
                </select>
            </div>

            <div>
                <label for="min_daily_limit" class="block text-sm font-semibold text-primary-700 mb-3">Daily Limit</label>
                <select name="min_daily_limit" class="w-full px-4 py-3 bg-white/60 border border-primary-200 rounded-xl text-primary-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    <option value="">Any Limit</option>
                    <option value="500" {{ ($filters['min_daily_limit'] ?? '') == '500' ? 'selected' : '' }}>500+/day</option>
                    <option value="1000" {{ ($filters['min_daily_limit'] ?? '') == '1000' ? 'selected' : '' }}>1000+/day</option>
                    <option value="5000" {{ ($filters['min_daily_limit'] ?? '') == '5000' ? 'selected' : '' }}>5000+/day</option>
                </select>
            </div>
        </form>

        <div class="flex justify-center mt-6">
            <button type="submit" form="filter-form" class="bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center">
                <i class="fas fa-search mr-2"></i>
                Apply Filters
            </button>
        </div>
    </div>

    <!-- Services Grid -->
    @if($listings->isEmpty())
        <div class="glass-effect rounded-2xl p-16 text-center animate-fade-in" style="animation-delay: 0.3s;">
            <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-accent-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-primary-900 mb-4">No services found</h3>
            <p class="text-xl text-primary-600 mb-8 max-w-md mx-auto">We couldn't find any SMTP services matching your criteria. Try adjusting your filters or check back later for new listings.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('marketplace.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-xl hover:from-accent-600 hover:to-primary-600 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Clear Filters
                </a>
                <a href="#featured" class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm text-primary-700 font-semibold rounded-xl hover:bg-white transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Browse Featured
                </a>
            </div>
        </div>
    @else
        <!-- Services Header -->
        <div class="flex items-center justify-between mb-8 animate-slide-up" style="animation-delay: 0.3s;">
            <div>
                <h2 class="text-2xl font-bold text-primary-900">Available Services</h2>
                <p class="text-primary-600 mt-1">{{ $listings->total() }} services found</p>
            </div>
            <a href="{{ route('marketplace.purchases') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-accent-600 to-accent-700 text-white font-semibold rounded-xl hover:from-accent-700 hover:to-accent-800 transition-all duration-300">
                <i class="fas fa-shopping-bag mr-2"></i>
                My Purchases
            </a>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 animate-fade-in" style="animation-delay: 0.4s;">
            @foreach($listings as $listing)
                <div class="group relative glass-effect rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1 border border-white/20">
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 via-transparent to-accent-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <div class="relative p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-primary-900 truncate group-hover:text-primary-700 transition-colors">{{ $listing->name }}</h3>
                                <div class="flex items-center mt-1">
                                    <p class="text-sm text-primary-600 truncate">{{ $listing->vendor->shop_name }}</p>
                                    @if($listing->vendor->is_verified)
                                        <svg class="w-4 h-4 text-blue-500 ml-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <p class="text-primary-600 mb-4 line-clamp-3 text-sm leading-relaxed">{{ $listing->description }}</p>

                        <!-- Features -->
                        @if($listing->features)
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach(array_slice($listing->features, 0, 3) as $feature)
                                    <span class="px-2 py-1 text-xs bg-gradient-to-r from-primary-100/50 to-accent-100/50 text-primary-700 rounded-full font-medium">{{ $feature }}</span>
                                @endforeach
                                @if(count($listing->features) > 3)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-primary-600 rounded-full font-medium">+{{ count($listing->features) - 3 }} more</span>
                                @endif
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t border-primary-100/50">
                            <div>
                                <div class="text-xl font-bold text-primary-600">{{ $listing->getFormattedPrice() }}</div>
                                <div class="text-xs text-primary-500 mt-1">{{ number_format($listing->daily_limit) }}/day limit</div>
                            </div>
                            <a href="{{ route('marketplace.show', $listing->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-lg hover:from-accent-600 hover:to-primary-600 transition-all duration-300 transform hover:scale-105 text-sm">
                                View Details
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($listings->hasPages())
            <div class="mt-12 flex justify-center animate-slide-up" style="animation-delay: 0.5s;">
                <div class="glass-effect rounded-2xl shadow-lg border border-white/20 p-4">
                    {{ $listings->links() }}
                </div>
            </div>
        @endif
    @endif
</x-app-layout>
