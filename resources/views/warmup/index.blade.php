@extends('layouts.app')

@section('title', 'Email Warmup')

@section('content')
<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Email Warmup</h1>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Email warmup gradually increases your sending volume to establish a positive sender reputation. 
                    This helps improve inbox delivery rates and prevents spam flagging.
                </p>
            </div>
        </div>
    </div>

    <!-- SMTP Accounts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SMTP Account</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Daily Limit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($smtpAccounts as $account)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $account->name }}</div>
                        <div class="text-sm text-gray-500">{{ $account->from_address }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($account->emailWarmup)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @switch($account->emailWarmup->status)
                                @case('active') bg-green-100 text-green-800 @break
                                @case('paused') bg-yellow-100 text-yellow-800 @break
                                @case('completed') bg-blue-100 text-blue-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ ucfirst($account->emailWarmup->status) }}
                        </span>
                        @else
                        <span class="text-gray-400">Not started</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($account->emailWarmup)
                        <div class="w-full bg-gray-200 rounded-full h-2 max-w-xs">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $account->emailWarmup->getProgress() }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $account->emailWarmup->getProgress() }}%</div>
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        @if($account->emailWarmup)
                        {{ $account->emailWarmup->current_daily_limit }} / {{ $account->emailWarmup->target_daily_limit }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        @if($account->emailWarmup)
                        {{ $account->emailWarmup->current_day }} / {{ $account->emailWarmup->total_days }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($account->emailWarmup)
                            @if($account->emailWarmup->isActive())
                            <form method="POST" action="{{ route('warmup.pause', $account->emailWarmup->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 text-sm">Pause</button>
                            </form>
                            @elseif($account->emailWarmup->isPaused())
                            <form method="POST" action="{{ route('warmup.resume', $account->emailWarmup->id) }}" class="inline mr-3">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 text-sm">Resume</button>
                            </form>
                            @endif
                            <a href="{{ route('warmup.show', $account->emailWarmup->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                        @else
                        <button type="button" class="text-indigo-600 hover:text-indigo-900 text-sm" onclick="showStartModal('{{ $account->id }}', '{{ $account->name }}')">
                            Start Warmup
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <p>No SMTP accounts found. Add an SMTP account first.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Start Warmup Modal -->
<div id="startModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium mb-4">Start Email Warmup</h3>
        <form method="POST" action="{{ route('warmup.start') }}">
            @csrf
            <input type="hidden" name="smtp_account_id" id="modalSmtpId">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Account</label>
                <input type="text" id="modalSmtpName" disabled class="w-full border-gray-300 rounded-md bg-gray-100">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Daily Limit</label>
                <input type="number" name="target_daily_limit" value="500" min="50" max="1000" class="w-full border-gray-300 rounded-md">
                <p class="text-xs text-gray-500 mt-1">Maximum emails per day after warmup</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Warmup Duration (days)</label>
                <input type="number" name="total_days" value="30" min="7" max="90" class="w-full border-gray-300 rounded-md">
                <p class="text-xs text-gray-500 mt-1">Total days to complete warmup</p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideStartModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Start Warmup
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showStartModal(id, name) {
    document.getElementById('modalSmtpId').value = id;
    document.getElementById('modalSmtpName').value = name;
    document.getElementById('startModal').classList.remove('hidden');
}

function hideStartModal() {
    document.getElementById('startModal').classList.add('hidden');
}
</script>
@endsection
