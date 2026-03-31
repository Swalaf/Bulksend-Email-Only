<x-app-layout title="SMTP Accounts">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 animate-slide-up">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 mb-2">SMTP Accounts</h1>
            <p class="text-primary-600">Manage your email delivery servers and configurations</p>
        </div>
        <a href="{{ route('smtp.create') }}"
           class="bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-server"></i>
            Add SMTP Account
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 animate-scale-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Total Accounts</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $accounts->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-server text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Active Accounts</p>
                    <p class="text-3xl font-bold text-accent-600">{{ $accounts->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Verified</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $accounts->where('status', 'verified')->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-shield-alt text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Today's Usage</p>
                    <p class="text-3xl font-bold text-accent-600">{{ $accounts->sum('emails_sent_today') }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-600 to-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 glass-effect border border-green-200/50 rounded-xl animate-slide-up">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500/20 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <p class="text-green-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 glass-effect border border-red-200/50 rounded-xl animate-slide-up">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-500/20 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <p class="text-red-300">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- SMTP Accounts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 animate-fade-in" style="animation-delay: 0.4s;">
        @forelse($accounts as $account)
            <div class="glass-effect rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-primary-100/50">
                <!-- Account Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg {{ !$account->is_active ? 'opacity-50' : '' }}">
                            <i class="fas fa-server text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-primary-900 flex items-center">
                                {{ $account->name }}
                                @if($account->is_default)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-accent-100 text-accent-700 rounded-lg border border-accent-200">Default</span>
                                @endif
                            </h3>
                            <p class="text-primary-600 text-sm">{{ $account->host }}:{{ $account->port }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        <!-- Status Badge -->
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                            @switch($account->status)
                                @case('verified') bg-accent-100 text-accent-800 border border-accent-200
                                @case('failed') bg-red-100 text-red-800 border border-red-200
                                @case('pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                @case('suspended') bg-gray-100 text-gray-800 border border-gray-200
                            @endswitch">
                            <i class="fas fa-circle text-xs mr-1 animate-pulse"></i>
                            {{ ucfirst($account->status) }}
                        </span>

                        <!-- Active/Inactive Indicator -->
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full {{ $account->is_active ? 'bg-accent-500' : 'bg-red-500' }} mr-1"></div>
                            <span class="text-xs text-primary-600">{{ $account->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Account Details -->
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-primary-600">From Address:</span>
                        <span class="text-sm font-medium text-primary-900">{{ $account->from_address }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-primary-600">Encryption:</span>
                        <span class="text-sm font-medium text-primary-900 uppercase">{{ $account->encryption ?: 'None' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-primary-600">Today's Usage:</span>
                        <span class="text-sm font-medium text-primary-900">{{ $account->emails_sent_today }}/{{ $account->daily_limit }}</span>
                    </div>
                </div>

                <!-- Usage Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-primary-600 mb-1">
                        <span>Daily Limit Usage</span>
                        <span>{{ round(($account->emails_sent_today / max($account->daily_limit, 1)) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-primary-100 rounded-full h-2">
                        <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-2 rounded-full transition-all duration-300"
                             style="width: {{ min(($account->emails_sent_today / max($account->daily_limit, 1)) * 100, 100) }}%"></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-primary-100/50">
                    <div class="flex space-x-2">
                        <a href="{{ route('smtp.edit', $account->id) }}"
                           class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                           title="Edit Account">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('smtp.test', $account->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="p-2 text-primary-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Test Connection">
                                <i class="fas fa-flask"></i>
                            </button>
                        </form>
                        @if(!$account->is_default)
                            <form action="{{ route('smtp.set-default', $account->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="p-2 text-primary-600 hover:text-accent-600 hover:bg-accent-50 rounded-lg transition-colors"
                                        title="Set as Default">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                    <form action="{{ route('smtp.toggle-active', $account->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="p-2 rounded-lg transition-colors {{ $account->is_active ? 'text-accent-600 hover:bg-accent-50' : 'text-red-600 hover:bg-red-50' }}"
                                title="{{ $account->is_active ? 'Deactivate Account' : 'Activate Account' }}">
                            @if($account->is_active)
                                <i class="fas fa-pause"></i>
                            @else
                                <i class="fas fa-play"></i>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="glass-effect rounded-2xl p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-100 to-accent-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-server text-5xl text-primary-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-primary-900 mb-2">No SMTP accounts yet</h3>
                    <p class="text-primary-600 mb-8 text-lg">Configure your first SMTP server to start sending emails at scale</p>
                    <a href="{{ route('smtp.create') }}"
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-accent-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Add Your First SMTP Account
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
