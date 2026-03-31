<x-app-layout title="Subscribers">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 animate-slide-up">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 mb-2">Email Subscribers</h1>
            <p class="text-primary-600">Manage your subscriber lists and contacts</p>
        </div>
        <a href="{{ route('subscribers.create') }}"
           class="bg-gradient-to-r from-accent-600 to-accent-700 hover:from-accent-700 hover:to-accent-800 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-user-plus"></i>
            Add Subscriber
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 animate-scale-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Total Subscribers</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $subscribers->total() ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Active Subscribers</p>
                    <p class="text-3xl font-bold text-accent-600">{{ $subscribers->where('status', 'active')->count() ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Subscriber Lists</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $subscriberLists->count() ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-accent-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-list text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="glass-effect rounded-2xl p-6 mb-8 animate-slide-up" style="animation-delay: 0.3s;">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-primary-700 mb-2">Search Subscribers</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                       class="w-full px-4 py-3 bg-white/50 border border-primary-200 rounded-xl text-primary-900 placeholder-primary-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-primary-700 mb-2">Status Filter</label>
                <select name="status" class="px-4 py-3 bg-white/50 border border-primary-200 rounded-xl text-primary-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                    <option value="bounced" {{ request('status') == 'bounced' ? 'selected' : '' }}>Bounced</option>
                </select>
            </div>
            <div>
                <label for="list_id" class="block text-sm font-medium text-primary-700 mb-2">Subscriber List</label>
                <select name="list_id" class="px-4 py-3 bg-white/50 border border-primary-200 rounded-xl text-primary-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                    <option value="">All Lists</option>
                    @foreach($subscriberLists as $list)
                        <option value="{{ $list->id }}" {{ request('list_id') == $list->id ? 'selected' : '' }}>{{ $list->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if(request()->hasAny(['search', 'status', 'list_id']))
                    <a href="{{ route('subscribers.index') }}" class="bg-primary-100 hover:bg-primary-200 text-primary-700 px-4 py-3 rounded-xl font-medium transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Subscribers Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 animate-fade-in" style="animation-delay: 0.4s;">
        @forelse($subscribers as $subscriber)
            <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-primary-100/50">
                <!-- Subscriber Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($subscriber->first_name ?: $subscriber->email, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-primary-900">
                                {{ $subscriber->first_name }} {{ $subscriber->last_name }}
                            </h3>
                            <p class="text-primary-600 text-sm">{{ $subscriber->email }}</p>
                        </div>
                    </div>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                        @if($subscriber->status === 'active') bg-accent-100 text-accent-800 border border-accent-200
                        @elseif($subscriber->status === 'unsubscribed') bg-red-100 text-red-800 border border-red-200
                        @else bg-yellow-100 text-yellow-800 border border-yellow-200 @endif">
                        {{ ucfirst($subscriber->status) }}
                    </span>
                </div>

                <!-- Subscriber Lists -->
                @if($subscriber->lists->count() > 0)
                    <div class="mb-4">
                        <p class="text-xs font-medium text-primary-600 mb-2">Lists:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($subscriber->lists as $list)
                                <span class="inline-flex px-2 py-1 text-xs bg-primary-100 text-primary-700 rounded-lg">
                                    {{ $list->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-primary-100/50">
                    <div class="flex space-x-2">
                        <a href="{{ route('subscribers.show', $subscriber->id) }}"
                           class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                           title="View Subscriber">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('subscribers.edit', $subscriber->id) }}"
                           class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                           title="Edit Subscriber">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <div class="text-xs text-primary-500">
                        {{ $subscriber->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="glass-effect rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-accent-100 to-accent-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-5xl text-accent-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-primary-900 mb-2">No subscribers yet</h3>
                    <p class="text-primary-600 mb-8 text-lg">Start building your email list by adding your first subscriber</p>
                    <a href="{{ route('subscribers.create') }}"
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-accent-600 to-accent-700 text-white font-semibold rounded-xl hover:from-accent-700 hover:to-accent-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        Add Your First Subscriber
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($subscribers->hasPages())
        <div class="mt-8 flex justify-center animate-fade-in" style="animation-delay: 0.6s;">
            <div class="glass-effect rounded-xl p-4">
                {{ $subscribers->appends(request()->query())->links() }}
            </div>
        </div>
    @endif>
</x-app-layout>
