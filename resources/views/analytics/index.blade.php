@extends('layouts.app')

@section('title', 'Analytics')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="py-6" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Analytics Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400">Track your email campaign performance</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Dark Mode Toggle -->
            <button 
                @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', darkMode)"
                class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
            >
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </button>

            <!-- Time Range Selector -->
            <select id="timeRange" class="border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm text-sm">
                <option value="7">Last 7 days</option>
                <option value="30" selected>Last 30 days</option>
                <option value="90">Last 90 days</option>
            </select>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Sent -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Sent</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($overview['total_sent']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ $overview['total_campaigns'] }} campaigns</p>
        </div>

        <!-- Open Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Open Rate</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $overview['open_rate'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ number_format($overview['total_opened']) }} opens</p>
        </div>

        <!-- Click Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Click Rate</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $overview['click_rate'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ number_format($overview['total_clicked']) }} clicks</p>
        </div>

        <!-- Bounce Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Bounce Rate</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $overview['bounce_rate'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ number_format($overview['total_bounced']) }} bounces</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Email Activity Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Email Activity</h2>
            <div class="relative h-72">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Engagement Funnel -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Engagement Funnel</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Sent</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($funnel['sent']) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Delivered</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($funnel['delivered']) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: {{ $funnel['rates']['delivery_rate'] }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Opened</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($funnel['opened']) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-yellow-500 h-3 rounded-full" style="width: {{ $funnel['rates']['open_rate'] }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Clicked</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($funnel['clicked']) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-purple-500 h-3 rounded-full" style="width: {{ $funnel['rates']['click_rate'] }}%"></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div>
                        <p class="text-xl font-bold text-green-600">{{ $funnel['rates']['delivery_rate'] }}%</p>
                        <p class="text-xs text-gray-500">Delivery</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-blue-600">{{ $funnel['rates']['open_rate'] }}%</p>
                        <p class="text-xs text-gray-500">Open</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-purple-600">{{ $funnel['rates']['click_rate'] }}%</p>
                        <p class="text-xs text-gray-500">Click</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Campaigns -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Top Campaigns</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Campaign</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Sent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Open Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Click Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topCampaigns as $campaign)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <a href="{{ route('campaigns.show', $campaign['id']) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $campaign['name'] }}
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $campaign['subject'] }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($campaign['sent']) }}</td>
                            <td class="px-6 py-4 text-sm text-green-600 font-medium">{{ $campaign['open_rate'] }}%</td>
                            <td class="px-6 py-4 text-sm text-indigo-600 font-medium">{{ $campaign['click_rate'] }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                No campaigns yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SMTP Usage -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">SMTP Usage</h2>
            </div>
            <div class="p-6">
                @forelse($smtpUsage as $account)
                <div class="mb-4 last:mb-0">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $account['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $account['from_address'] }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $account['is_active'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $account['is_active'] ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                        <span>Today: {{ $account['usage_today'] }} / {{ $account['daily_limit'] }}</span>
                        <span>{{ $account['usage_percentage'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $account['usage_percentage'] > 80 ? 'bg-red-500' : 'bg-indigo-500' }}" 
                             style="width: {{ min(100, $account['usage_percentage']) }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-4">No SMTP accounts configured</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Realtime Stats (if campaigns sending) -->
    @if($realtime['active_campaigns'] > 0)
    <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
            <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                {{ $realtime['active_campaigns'] }} campaign(s) sending - {{ number_format($realtime['total_in_queue']) }} in queue
            </span>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#9ca3af' : '#6b7280';
    const gridColor = isDark ? '#374151' : '#e5e7eb';

    // Activity Chart
    const ctx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($timeSeries, 'date')) !!},
            datasets: [
                {
                    label: 'Sent',
                    data: {!! json_encode(array_column($timeSeries, 'sent')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Opened',
                    data: {!! json_encode(array_column($timeSeries, 'opened')) !!},
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Clicked',
                    data: {!! json_encode(array_column($timeSeries, 'clicked')) !!},
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: textColor }
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor },
                    ticks: { color: textColor }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: { color: textColor }
                }
            }
        }
    });

    // Handle time range change
    document.getElementById('timeRange').addEventListener('change', function() {
        const days = this.value;
        fetch(`/analytics/chart?type=time_series&days=${days}`)
            .then(response => response.json())
            .then(data => {
                activityChart.data.labels = data.map(d => d.date);
                activityChart.data.datasets[0].data = data.map(d => d.sent);
                activityChart.data.datasets[1].data = data.map(d => d.opened);
                activityChart.data.datasets[2].data = data.map(d => d.clicked);
                activityChart.update();
            });
    });

    // Dark mode toggle observer
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                const isDark = document.documentElement.classList.contains('dark');
                activityChart.options.plugins.legend.labels.color = isDark ? '#9ca3af' : '#6b7280';
                activityChart.options.scales.x.grid.color = isDark ? '#374151' : '#e5e7eb';
                activityChart.options.scales.y.grid.color = isDark ? '#374151' : '#e5e7eb';
                activityChart.options.scales.x.ticks.color = isDark ? '#9ca3af' : '#6b7280';
                activityChart.options.scales.y.ticks.color = isDark ? '#9ca3af' : '#6b7280';
                activityChart.update();
            }
        });
    });
    observer.observe(document.documentElement, { attributes: true });
});
</script>
@endpush
@endsection
