<!-- Sidebar Navigation -->
<div class="flex flex-col h-full">
    <!-- Logo -->
    <div class="flex items-center justify-center py-8 px-6 border-b border-primary-200/20">
        <a href="{{ route('dashboard') }}" class="flex items-center group">
            <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <span class="text-xl font-bold text-primary-900">BulkSend</span>
                <div class="text-xs text-primary-600">Email Marketing</div>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('dashboard') ? 'nav-item-active text-primary-700' : 'text-primary-600 hover:text-primary-700 hover:bg-primary-50' }}">
            <i class="fas fa-tachometer-alt mr-3 text-lg group-hover:scale-110 transition-transform"></i>
            Dashboard
            @if(request()->routeIs('dashboard'))
                <div class="ml-auto w-2 h-2 bg-primary-600 rounded-full animate-pulse"></div>
            @endif
        </a>

        <!-- Campaigns -->
        <a href="{{ route('campaigns.index') }}"
           class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('campaigns.*') ? 'nav-item-active text-primary-700' : 'text-primary-600 hover:text-primary-700 hover:bg-primary-50' }}">
            <i class="fas fa-envelope mr-3 text-lg group-hover:scale-110 transition-transform"></i>
            Campaigns
            @if(request()->routeIs('campaigns.*'))
                <div class="ml-auto w-2 h-2 bg-primary-600 rounded-full animate-pulse"></div>
            @endif
        </a>

        <!-- Subscribers -->
        <a href="{{ route('subscribers.index') }}"
           class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('subscribers.*') ? 'nav-item-active text-primary-700' : 'text-primary-600 hover:text-primary-700 hover:bg-primary-50' }}">
            <i class="fas fa-users mr-3 text-lg group-hover:scale-110 transition-transform"></i>
            Subscribers
            @if(request()->routeIs('subscribers.*'))
                <div class="ml-auto w-2 h-2 bg-primary-600 rounded-full animate-pulse"></div>
            @endif
        </a>

        <!-- SMTP Accounts -->
        <a href="{{ route('smtp.index') }}"
           class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('smtp.*') ? 'nav-item-active text-primary-700' : 'text-primary-600 hover:text-primary-700 hover:bg-primary-50' }}">
            <i class="fas fa-server mr-3 text-lg group-hover:scale-110 transition-transform"></i>
            SMTP Accounts
            @if(request()->routeIs('smtp.*'))
                <div class="ml-auto w-2 h-2 bg-primary-600 rounded-full animate-pulse"></div>
            @endif
        </a>

        <!-- Analytics -->
        <a href="{{ route('analytics.index') }}"
           class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('analytics.*') ? 'nav-item-active text-accent-700' : 'text-primary-600 hover:text-primary-700 hover:bg-primary-50' }}">
            <i class="fas fa-chart-bar mr-3 text-lg group-hover:scale-110 transition-transform"></i>
            Analytics
            @if(request()->routeIs('analytics.*'))
                <div class="ml-auto w-2 h-2 bg-accent-500 rounded-full animate-pulse"></div>
            @endif
        </a>

        <!-- Marketplace -->
        <a href="{{ route('marketplace.index') }}"
           class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 group {{ request()->routeIs('marketplace.*') ? 'nav-item-active text-accent-700' : 'text-primary-600 hover:text-primary-700 hover:bg-primary-50' }}">
            <i class="fas fa-store mr-3 text-lg group-hover:scale-110 transition-transform"></i>
            Marketplace
            @if(request()->routeIs('marketplace.*'))
                <div class="ml-auto w-2 h-2 bg-accent-500 rounded-full animate-pulse"></div>
            @endif
        </a>
    </nav>

    <!-- Bottom Section -->
    <div class="p-4 border-t border-primary-200/20">
        <!-- Quick Actions -->
        <div class="mb-4">
            <h3 class="text-xs font-semibold text-primary-600 uppercase tracking-wider mb-3">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('campaigns.create') }}" class="flex items-center px-3 py-2 text-sm text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors group">
                    <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
                    New Campaign
                </a>
                <a href="{{ route('subscribers.create') }}" class="flex items-center px-3 py-2 text-sm text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors group">
                    <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform"></i>
                    Add Subscriber
                </a>
            </div>
        </div>

        <!-- User Stats -->
        <div class="glass-effect rounded-xl p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-primary-600">Account Status</span>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-accent-500 rounded-full animate-pulse mr-1"></div>
                    <span class="text-xs text-accent-600 font-medium">Active</span>
                </div>
            </div>
            <div class="space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="text-primary-600">Emails Sent</span>
                    <span class="font-medium text-primary-900">{{ auth()->user()->campaigns()->sum('sent_count') ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-primary-600">Subscribers</span>
                    <span class="font-medium text-primary-900">{{ auth()->user()->subscribers()->count() ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>