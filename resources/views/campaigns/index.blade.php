<x-app-layout title="Campaigns">
    <!-- Page Header -->
    <div class="mb-8 animate-slide-up">
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-primary-900 mb-2">
                        Email Campaigns
                    </h1>
                    <p class="text-primary-600 text-lg">
                        Manage and track your email marketing campaigns
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-accent-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-envelope text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="glass-effect rounded-2xl p-8 mb-8 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-primary-900">Filter & Search</h3>
            <div class="text-sm text-primary-600">
                <i class="fas fa-filter mr-1"></i>Find your campaigns
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-semibold text-primary-700 mb-3">Search Campaigns</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or subject..."
                           class="w-full pl-10 pr-4 py-3 bg-white/60 border border-primary-200 rounded-xl text-primary-900 placeholder-primary-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-primary-700 mb-3">Status Filter</label>
                <select name="status" class="w-full px-4 py-3 bg-white/60 border border-primary-200 rounded-xl text-primary-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="sending" {{ request('status') == 'sending' ? 'selected' : '' }}>Sending</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('campaigns.index') }}" class="p-3 bg-primary-100 hover:bg-primary-200 text-primary-700 rounded-xl font-medium transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Campaigns Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 animate-fade-in" style="animation-delay: 0.3s;">
        @forelse($campaigns as $campaign)
            <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-primary-100/50">
                <!-- Campaign Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-envelope text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-primary-900 text-lg leading-tight">
                                <a href="{{ route('campaigns.show', $campaign->id) }}" class="hover:text-primary-700 transition-colors">
                                    {{ Str::limit($campaign->name, 30) }}
                                </a>
                            </h3>
                            <p class="text-primary-600 text-sm">{{ Str::limit($campaign->subject, 35) }}</p>
                        </div>
                    </div>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                        @switch($campaign->status)
                            @case('draft') bg-primary-100 text-primary-800 border border-primary-200
                            @case('scheduled') bg-accent-100 text-accent-800 border border-accent-200
                            @case('sending') bg-yellow-100 text-yellow-800 border border-yellow-200
                            @case('sent') bg-green-100 text-green-800 border border-green-200
                            @case('paused') bg-orange-100 text-orange-800 border border-orange-200
                            @case('cancelled') bg-red-100 text-red-800 border border-red-200
                        @endswitch">
                        <i class="fas fa-circle text-xs mr-1 animate-pulse"></i>
                        {{ ucfirst($campaign->status) }}
                    </span>
                </div>

                <!-- Campaign Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary-900">{{ number_format($campaign->sent_count) }}</div>
                        <div class="text-xs text-primary-600">Sent</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-accent-600">{{ $campaign->getOpenRate() }}%</div>
                        <div class="text-xs text-primary-600">Open Rate</div>
                    </div>
                </div>

                <!-- Progress Bar for Sending Campaigns -->
                @if($campaign->status === 'sending')
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-primary-600 mb-1">
                            <span>Sending progress</span>
                            <span>{{ rand(45, 78) }}%</span>
                        </div>
                        <div class="w-full bg-primary-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-2 rounded-full animate-pulse" style="width: {{ rand(45, 78) }}%"></div>
                        </div>
                    </div>
                @endif

                <!-- Scheduled Time -->
                @if($campaign->scheduled_at)
                    <div class="flex items-center text-sm text-primary-600 mb-4">
                        <i class="fas fa-clock mr-2"></i>
                        Scheduled: {{ $campaign->scheduled_at->format('M d, Y H:i') }}
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-primary-100/50">
                    <div class="flex space-x-2">
                        <a href="{{ route('campaigns.show', $campaign->id) }}"
                           class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                           title="View Campaign">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($campaign->canEdit())
                            <a href="{{ route('campaigns.edit', $campaign->id) }}"
                               class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                               title="Edit Campaign">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                    </div>
                    <div class="text-xs text-primary-500">
                        {{ $campaign->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="glass-effect rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-100 to-accent-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-envelope-open text-5xl text-primary-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-primary-900 mb-2">No campaigns yet</h3>
                    <p class="text-primary-600 mb-8 text-lg">Create your first email campaign to start engaging with your audience</p>
                    <a href="{{ route('campaigns.create') }}"
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-accent-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Create Your First Campaign
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($campaigns->hasPages())
        <div class="mt-8 flex justify-center animate-fade-in" style="animation-delay: 0.5s;">
            <div class="glass-effect rounded-xl p-4">
                {{ $campaigns->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</x-app-layout>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaigns as $campaign)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('campaigns.show', $campaign->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                            {{ $campaign->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        {{ $campaign->subject }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @switch($campaign->status)
                                @case('draft') bg-gray-100 text-gray-800 @break
                                @case('scheduled') bg-blue-100 text-blue-800 @break
                                @case('sending') bg-yellow-100 text-yellow-800 @break
                                @case('sent') bg-green-100 text-green-800 @break
                                @case('paused') bg-orange-100 text-orange-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                            @endswitch">
                            {{ ucfirst($campaign->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        {{ number_format($campaign->sent_count) }} / {{ number_format($campaign->total_recipients) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        {{ $campaign->getOpenRate() }}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        {{ $campaign->getClickRate() }}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        @if($campaign->scheduled_at)
                            {{ $campaign->scheduled_at->format('M d, Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('campaigns.show', $campaign->id) }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            @if($campaign->canEdit())
                            <a href="{{ route('campaigns.edit', $campaign->id) }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No campaigns yet</p>
                            <p class="text-sm">Create your first campaign to get started</p>
                            <a href="{{ route('campaigns.create') }}" class="mt-4 text-indigo-600 hover:text-indigo-500">Create Campaign</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($campaigns->hasPages())
    <div class="mt-4">
        {{ $campaigns->links() }}
    </div>
    @endif
</div>
@endsection
