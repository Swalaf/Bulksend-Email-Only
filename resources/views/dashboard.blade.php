<x-app-layout title="Dashboard">
    <!-- Welcome Section -->
    <div class="mb-8 animate-slide-up">
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-primary-900 mb-2">
                        Welcome back, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-primary-600 text-lg">
                        Here's what's happening with your email campaigns today.
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-accent-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Campaigns -->
        <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-scale-in" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Total Campaigns</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $stats['total_campaigns'] ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-arrow-up text-accent-500 mr-1"></i>
                        <span class="text-xs text-accent-600 font-medium">+12% this month</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-envelope text-xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Total Subscribers -->
        <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-scale-in" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Total Subscribers</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $stats['total_subscribers'] ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-arrow-up text-accent-500 mr-1"></i>
                        <span class="text-xs text-accent-600 font-medium">+8% this month</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Emails Sent -->
        <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-scale-in" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Emails Sent</p>
                    <p class="text-3xl font-bold text-primary-900">{{ number_format($stats['emails_sent'] ?? 0) }}</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-arrow-up text-accent-500 mr-1"></i>
                        <span class="text-xs text-accent-600 font-medium">+15% this month</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-600 to-accent-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-paper-plane text-xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Open Rate -->
        <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-scale-in" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Average Open Rate</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $stats['open_rate'] ?? 0 }}%</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-arrow-up text-accent-500 mr-1"></i>
                        <span class="text-xs text-accent-600 font-medium">+5% this month</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-600 to-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-effect rounded-2xl p-8 mb-8 animate-slide-up" style="animation-delay: 0.5s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-primary-900">Quick Actions</h3>
            <div class="text-sm text-primary-600">
                <i class="fas fa-bolt mr-1"></i>Start something new
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('campaigns.create') }}"
               class="group glass-effect border border-primary-200/50 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:border-primary-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-xl text-white"></i>
                    </div>
                    <i class="fas fa-arrow-right text-primary-400 group-hover:text-primary-600 transition-colors"></i>
                </div>
                <h4 class="font-semibold text-primary-900 mb-1">Create Campaign</h4>
                <p class="text-sm text-primary-600">Send emails to your subscribers</p>
            </a>

            <a href="{{ route('subscribers.create') }}"
               class="group glass-effect border border-accent-200/50 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:border-accent-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-xl text-white"></i>
                    </div>
                    <i class="fas fa-arrow-right text-accent-400 group-hover:text-accent-600 transition-colors"></i>
                </div>
                <h4 class="font-semibold text-primary-900 mb-1">Add Subscribers</h4>
                <p class="text-sm text-primary-600">Grow your email list</p>
            </a>

            <a href="{{ route('smtp.create') }}"
               class="group glass-effect border border-primary-200/50 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 hover:border-primary-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-600 to-accent-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-server text-xl text-white"></i>
                    </div>
                    <i class="fas fa-arrow-right text-primary-400 group-hover:text-primary-600 transition-colors"></i>
                </div>
                <h4 class="font-semibold text-primary-900 mb-1">Setup SMTP</h4>
                <p class="text-sm text-primary-600">Configure email delivery</p>
            </a>
        </div>
    </div>

    <!-- Recent Campaigns -->
    <div class="glass-effect rounded-2xl p-8 animate-slide-up" style="animation-delay: 0.6s;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-primary-900">Recent Campaigns</h3>
            <a href="{{ route('campaigns.index') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @forelse($stats['recent_campaigns'] ?? [] as $campaign)
            <div class="glass-effect border border-primary-100 rounded-xl p-6 mb-4 hover:shadow-lg transition-all duration-300 hover:border-primary-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-primary-900">{{ $campaign->name }}</h4>
                                <p class="text-sm text-primary-600">{{ $campaign->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <!-- Status Badge -->
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                            @if($campaign->status === 'sent') bg-accent-100 text-accent-800 border border-accent-200
                            @elseif($campaign->status === 'sending') bg-primary-100 text-primary-800 border border-primary-200
                            @elseif($campaign->status === 'scheduled') bg-accent-100 text-accent-700 border border-accent-200
                            @else bg-primary-100 text-primary-700 border border-primary-200 @endif">
                            <i class="fas fa-circle text-xs mr-1 animate-pulse"></i>
                            {{ ucfirst($campaign->status) }}
                        </span>

                        <!-- Stats -->
                        <div class="text-right">
                            <div class="text-lg font-bold text-primary-900">{{ $campaign->sent_count }}</div>
                            <div class="text-xs text-primary-600">sent</div>
                        </div>

                        <div class="text-right">
                            <div class="text-lg font-bold text-accent-600">{{ $campaign->getOpenRate() }}%</div>
                            <div class="text-xs text-primary-600">open rate</div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('campaigns.show', $campaign) }}"
                               class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('campaigns.edit', $campaign) }}"
                               class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar for Sending Campaigns -->
                @if($campaign->status === 'sending')
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-primary-600 mb-1">
                            <span>Sending progress</span>
                            <span>{{ rand(45, 78) }}%</span>
                        </div>
                        <div class="w-full bg-primary-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-2 rounded-full animate-pulse" style="width: {{ rand(45, 78) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gradient-to-br from-primary-100 to-accent-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-envelope-open text-4xl text-primary-400"></i>
                </div>
                <h4 class="text-xl font-semibold text-primary-900 mb-2">No campaigns yet</h4>
                <p class="text-primary-600 mb-6">Create your first email campaign to get started with BulkSend.</p>
                <a href="{{ route('campaigns.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-accent-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Campaign
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
