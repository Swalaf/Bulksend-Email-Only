@extends('layouts.app')

@section('title', 'Warmup Details')

@section('content')
<div class="py-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Warmup Details</h1>
            <p class="text-gray-500">{{ $warmup->smtpAccount->name }}</p>
        </div>
        <div class="flex gap-2">
            @if($warmup->isActive())
            <form method="POST" action="{{ route('warmup.pause', $warmup->id) }}">
                @csrf
                <button type="submit" class="px-3 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                    Pause
                </button>
            </form>
            @elseif($warmup->isPaused())
            <form method="POST" action="{{ route('warmup.resume', $warmup->id) }}">
                @csrf
                <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Resume
                </button>
            </form>
            @endif
            <a href="{{ route('warmup.index') }}" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Back
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="rounded-lg p-4 mb-6 
        @if($warmup->isActive()) bg-green-50 border border-green-200 @endif
        @if($warmup->isPaused()) bg-yellow-50 border border-yellow-200 @endif
        @if($warmup->isCompleted()) bg-blue-50 border border-blue-200 @endif">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="px-2 inline-flex text-sm font-semibold rounded-full 
                    @if($warmup->isActive()) bg-green-100 text-green-800 @endif
                    @if($warmup->isPaused()) bg-yellow-100 text-yellow-800 @endif
                    @if($warmup->isCompleted()) bg-blue-100 text-blue-800 @endif">
                    {{ ucfirst($warmup->status) }}
                </span>
                <span class="ml-3 text-gray-600">Day {{ $warmup->current_day }} of {{ $warmup->total_days }}</span>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ $warmup->current_daily_limit }}</div>
                <div class="text-sm text-gray-500">emails/day</div>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500 mb-1">
                <span>Progress</span>
                <span>{{ $warmup->getProgress() }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-indigo-600 h-3 rounded-full transition-all" style="width: {{ $warmup->getProgress() }}%"></div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 mb-1">Total Sent</div>
            <div class="text-2xl font-bold">{{ number_format($stats['total_sent']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 mb-1">Delivered</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($stats['total_delivered']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 mb-1">Opened</div>
            <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_opened']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 mb-1">Replied</div>
            <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_replied']) }}</div>
        </div>
    </div>

    <!-- Warmup Schedule -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-medium">Warmup Schedule</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-7 gap-2">
                @for($day = 1; $day <= min($warmup->total_days, 28); $day++)
                <div class="text-center p-2 rounded @if($day <= $warmup->current_day) bg-green-100 text-green-800 @elseif($day == $warmup->current_day + 1) bg-yellow-100 text-yellow-800 @else bg-gray-50 text-gray-400 @endif">
                    <div class="text-xs font-medium">{{ $day }}</div>
                    <div class="text-xs">
                        @php
                        $dailyLimit = 10;
                        if ($warmup->total_days > 0) {
                            $progress = $day / $warmup->total_days;
                            $dailyLimit = round(10 + ($warmup->target_daily_limit - 10) * $progress);
                        }
                        @endphp
                        {{ $dailyLimit }}
                    </div>
                </div>
                @endfor
            </div>
            @if($warmup->total_days > 28)
            <p class="text-sm text-gray-500 mt-3">+ {{ $warmup->total_days - 28 }} more days</p>
            @endif
        </div>
    </div>

    <!-- Recent Emails -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-medium">Recent Warmup Emails</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recipient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sent</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($warmup->warmupEmails()->latest()->limit(20)->get() as $email)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $email->recipient_email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($email->type) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $email->subject }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @switch($email->status)
                                    @case('pending') bg-gray-100 text-gray-800 @break
                                    @case('sent') bg-blue-100 text-blue-800 @break
                                    @case('delivered') bg-yellow-100 text-yellow-800 @break
                                    @case('opened') bg-green-100 text-green-800 @break
                                    @case('replied') bg-purple-100 text-purple-800 @break
                                @endswitch">
                                {{ ucfirst($email->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($email->sent_at)
                            {{ $email->sent_at->format('M d, H:i') }}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            No warmup emails sent yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
