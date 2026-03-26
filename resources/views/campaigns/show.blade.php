@extends('layouts.app')

@section('title', $campaign->name)

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $campaign->name }}</h1>
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
                </div>
                <p class="text-gray-500">{{ $campaign->subject }}</p>
            </div>
            <div class="flex gap-2">
                @if($campaign->canEdit())
                <a href="{{ route('campaigns.edit', $campaign->id) }}" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Edit
                </a>
                @endif
                <form method="POST" action="{{ route('campaigns.duplicate', $campaign->id) }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Duplicate
                    </button>
                </form>
                @if($campaign->isDraft())
                <form method="POST" action="{{ route('campaigns.cancel', $campaign->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 border border-red-300 rounded-md text-red-700 hover:bg-red-50">
                        Delete
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-500 mb-1">Recipients</div>
                <div class="text-2xl font-bold">{{ number_format($campaign->sent_count) }} <span class="text-sm font-normal text-gray-400">/ {{ number_format($campaign->total_recipients) }}</span></div>
                @if($campaign->isSending())
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $campaign->getProgress() }}%"></div>
                </div>
                @endif
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-500 mb-1">Open Rate</div>
                <div class="text-2xl font-bold text-green-600">{{ $campaign->getOpenRate() }}%</div>
                <div class="text-sm text-gray-400">{{ number_format($campaign->opened_count) }} opens</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-500 mb-1">Click Rate</div>
                <div class="text-2xl font-bold text-blue-600">{{ $campaign->getClickRate() }}%</div>
                <div class="text-sm text-gray-400">{{ number_format($campaign->clicked_count) }} clicks</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-500 mb-1">Bounce Rate</div>
                <div class="text-2xl font-bold text-red-600">{{ $campaign->getBounceRate() }}%</div>
                <div class="text-sm text-gray-400">{{ number_format($campaign->bounced_count) }} bounces</div>
            </div>
        </div>

        <!-- Campaign Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Email Preview -->
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-medium">Email Preview</h2>
                    </div>
                    <div class="p-6">
                        <div class="border rounded-lg overflow-hidden">
                            <iframe srcdoc="{{ $campaign->html_content }}" class="w-full h-96 border-0"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Links -->
                @if($campaign->links->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-medium">Tracked Links</h2>
                    </div>
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Original URL</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Clicks</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase">Unique</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($campaign->links as $link)
                                <tr>
                                    <td class="text-sm text-gray-500 truncate max-w-xs">{{ $link->url }}</td>
                                    <td class="text-sm">{{ number_format($link->click_count) }}</td>
                                    <td class="text-sm">{{ number_format($link->unique_click_count) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Details -->
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-medium">Details</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <div class="text-sm text-gray-500">SMTP Account</div>
                            <div class="font-medium">{{ $campaign->smtpAccount->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Created</div>
                            <div class="font-medium">{{ $campaign->created_at->format('M d, Y H:i') }}</div>
                        </div>
                        @if($campaign->scheduled_at)
                        <div>
                            <div class="text-sm text-gray-500">Scheduled</div>
                            <div class="font-medium">{{ $campaign->scheduled_at->format('M d, Y H:i') }}</div>
                        </div>
                        @endif
                        @if($campaign->started_at)
                        <div>
                            <div class="text-sm text-gray-500">Started</div>
                            <div class="font-medium">{{ $campaign->started_at->format('M d, Y H:i') }}</div>
                        </div>
                        @endif
                        @if($campaign->completed_at)
                        <div>
                            <div class="text-sm text-gray-500">Completed</div>
                            <div class="font-medium">{{ $campaign->completed_at->format('M d, Y H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($campaign->isDraft() || $campaign->isScheduled())
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-medium mb-4">Send Campaign</h2>
                    <p class="text-sm text-gray-500 mb-4">Select subscribers to send this campaign to.</p>
                    <a href="{{ route('subscribers.index') }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Select Subscribers
                    </a>
                </div>
                @endif

                @if($campaign->isSending())
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-medium mb-4">Sending Controls</h2>
                    <form method="POST" action="{{ route('campaigns.pause', $campaign->id) }}" class="mb-2">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                            Pause Sending
                        </button>
                    </form>
                    <form method="POST" action="{{ route('campaigns.cancel', $campaign->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50">
                            Cancel Campaign
                        </button>
                    </form>
                </div>
                @endif

                @if($campaign->isSent())
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-medium mb-4">Campaign Report</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Sent</span>
                            <span class="font-medium">{{ number_format($campaign->sent_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Opened</span>
                            <span class="font-medium">{{ number_format($campaign->opened_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Clicked</span>
                            <span class="font-medium">{{ number_format($campaign->clicked_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Bounced</span>
                            <span class="font-medium">{{ number_format($campaign->bounced_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Unsubscribed</span>
                            <span class="font-medium">{{ number_format($campaign->unsubscribed_count) }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
