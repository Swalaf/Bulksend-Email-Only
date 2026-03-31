<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('A/B Test Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $campaign->name }}</h3>
                        <p class="text-sm text-gray-600">A/B Test Results • Started: {{ $abTest->created_at->format('M j, Y g:i A') }}</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $abTest->status === 'running' ? 'Running' : 'Completed' }}
                            </span>
                        </div>
                    </div>

                    @if($abTest->status === 'completed')
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-green-800">Test Completed</h4>
                                    <p class="text-sm text-green-700">Winner determined based on {{ $abTest->winner_criteria }} • Declared: {{ $abTest->completed_at?->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Test Overview Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $abTest->variations->count() }}</div>
                            <div class="text-sm text-gray-600">Variations</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $totalEmails }}</div>
                            <div class="text-sm text-gray-600">Total Emails Sent</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $totalOpens }}</div>
                            <div class="text-sm text-gray-600">Total Opens</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $totalClicks }}</div>
                            <div class="text-sm text-gray-600">Total Clicks</div>
                        </div>
                    </div>

                    <!-- Variations Comparison -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Variations Comparison</h4>
                        <div class="space-y-4">
                            @foreach($abTest->variations as $variation)
                                <div class="border border-gray-200 rounded-lg p-6 {{ $variation->is_winner ? 'border-green-300 bg-green-50' : '' }}">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <h5 class="text-md font-medium text-gray-900">Variation {{ $loop->iteration }}</h5>
                                            @if($variation->is_winner)
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Winner
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ $variation->emails_sent }} emails sent
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="text-sm font-medium text-gray-700 mb-1">Subject:</div>
                                        <div class="text-sm text-gray-900 bg-gray-100 p-2 rounded">{{ $variation->subject }}</div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-blue-600">{{ number_format($variation->open_rate, 1) }}%</div>
                                            <div class="text-sm text-gray-600">Open Rate</div>
                                            <div class="text-xs text-gray-500">{{ $variation->opens }} opens</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-green-600">{{ number_format($variation->click_rate, 1) }}%</div>
                                            <div class="text-sm text-gray-600">Click Rate</div>
                                            <div class="text-xs text-gray-500">{{ $variation->clicks }} clicks</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-purple-600">{{ number_format($variation->click_to_open_rate, 1) }}%</div>
                                            <div class="text-sm text-gray-600">CTOR</div>
                                            <div class="text-xs text-gray-500">Click-to-Open Rate</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($abTest->status === 'completed')
                        <div class="flex space-x-4">
                            <form method="POST" action="{{ route('campaigns.ab-test.apply-winner', $campaign) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Apply Winner to Campaign
                                </button>
                            </form>

                            <a href="{{ route('campaigns.show', $campaign) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Back to Campaign
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-4">Test is still running. Results will be updated automatically.</p>
                            <a href="{{ route('campaigns.show', $campaign) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Back to Campaign
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>