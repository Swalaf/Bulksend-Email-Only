<x-app-layout title="Analytics">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 animate-slide-up">
        <div>
            <h1 class="text-3xl font-bold text-primary-900 mb-2">Email Analytics</h1>
            <p class="text-primary-600">Track your campaign performance and audience engagement</p>
        </div>
        <div class="flex items-center space-x-4">
            <select class="px-4 py-2 bg-white/50 border border-primary-200 rounded-xl text-primary-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 90 days</option>
            </select>
        </div>
    </div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade-in" style="animation-delay: 0.2s;">
        <!-- Total Campaigns -->
        <div class="glass-effect rounded-2xl p-6 animate-scale-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Total Campaigns</p>
                    <p class="text-3xl font-bold text-primary-900">{{ $overview['total_campaigns'] ?? 0 }}</p>
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

        <!-- Total Sent -->
        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Emails Sent</p>
                    <p class="text-3xl font-bold text-primary-900">{{ number_format($overview['total_sent'] ?? 0) }}</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-arrow-up text-accent-500 mr-1"></i>
                        <span class="text-xs text-accent-600 font-medium">+8% this month</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-paper-plane text-xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Open Rate -->
        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Average Open Rate</p>
                    <p class="text-3xl font-bold text-accent-600">{{ $overview['open_rate'] ?? 0 }}%</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-eye text-primary-500 mr-1"></i>
                        <span class="text-xs text-primary-600 font-medium">Industry avg: 22%</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-600 to-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-eye text-xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Click Rate -->
        <div class="glass-effect rounded-2xl p-6 animate-scale-in" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-primary-600 mb-1">Average Click Rate</p>
                    <p class="text-3xl font-bold text-accent-700">{{ $overview['click_rate'] ?? 0 }}%</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-mouse-pointer text-accent-500 mr-1"></i>
                        <span class="text-xs text-accent-600 font-medium">Industry avg: 2.5%</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-700 to-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-mouse-pointer text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Time Series Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Activity (Last 30 Days)</h3>
                        <div class="h-64">
                            <canvas id="timeSeriesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Campaigns -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Campaigns</h3>
                        <div class="space-y-4">
                            @forelse($topCampaigns ?? [] as $campaign)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $campaign['name'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $campaign['subject'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Sent: {{ $campaign['sent'] }}</p>
                                        <p class="text-sm text-green-600">Open: {{ $campaign['open_rate'] }}%</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-8">No campaigns yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Engagement Funnel -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Engagement Funnel</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($funnel['sent'] ?? 0) }}</p>
                            <p class="text-sm text-blue-800">Sent</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">{{ number_format($funnel['delivered'] ?? 0) }}</p>
                            <p class="text-sm text-green-800">Delivered</p>
                            <p class="text-xs text-green-600">{{ $funnel['rates']['delivery_rate'] ?? 0 }}%</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-2xl font-bold text-purple-600">{{ number_format($funnel['opened'] ?? 0) }}</p>
                            <p class="text-sm text-purple-800">Opened</p>
                            <p class="text-xs text-purple-600">{{ $funnel['rates']['open_rate'] ?? 0 }}%</p>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <p class="text-2xl font-bold text-red-600">{{ number_format($funnel['clicked'] ?? 0) }}</p>
                            <p class="text-sm text-red-800">Clicked</p>
                            <p class="text-xs text-red-600">{{ $funnel['rates']['click_rate'] ?? 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('timeSeriesChart').getContext('2d');
            const timeSeriesData = @json($timeSeries ?? []);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: timeSeriesData.map(d => d.date),
                    datasets: [{
                        label: 'Sent',
                        data: timeSeriesData.map(d => d.sent),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Opened',
                        data: timeSeriesData.map(d => d.opened),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Clicked',
                        data: timeSeriesData.map(d => d.clicked),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
