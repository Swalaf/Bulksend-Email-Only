<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('SMTP Accounts') }}
            </h2>
            <a href="{{ route('smtp.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add SMTP
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            @if($accounts->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No SMTP accounts yet</h3>
                    <p class="text-gray-500 mb-6">Add your first SMTP account to start sending emails.</p>
                    <a href="{{ route('smtp.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add SMTP Account
                    </a>
                </div>
            @else
                <!-- SMTP List -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($accounts as $account)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center 
                                            {{ $account->is_verified ? 'bg-green-100' : 'bg-gray-100' }}">
                                            <svg class="w-5 h-5 {{ $account->is_verified ? 'text-green-600' : 'text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="font-semibold text-gray-900 flex items-center">
                                                {{ $account->name }}
                                                @if($account->is_default)
                                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-700 rounded">Default</span>
                                                @endif
                                            </h3>
                                            <p class="text-sm text-gray-500">{{ $account->host }}:{{ $account->port }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $account->status === 'verified' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $account->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $account->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $account->status === 'suspended' ? 'bg-gray-100 text-gray-700' : '' }}">
                                        {{ ucfirst($account->status) }}
                                    </span>
                                </div>

                                <!-- Details -->
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">From:</span>
                                        <span class="text-gray-900">{{ $account->from_address }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Encryption:</span>
                                        <span class="text-gray-900 uppercase">{{ $account->encryption }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Daily Limit:</span>
                                        <span class="text-gray-900">{{ $account->emails_sent_today }}/{{ $account->daily_limit }}</span>
                                    </div>
                                </div>

                                <!-- Usage Bar -->
                                <div class="mt-4">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Today's usage</span>
                                        <span>{{ round(($account->emails_sent_today / $account->daily_limit) * 100) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($account->emails_sent_today / $account->daily_limit) * 100 }}%"></div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('smtp.edit', $account->id) }}" 
                                           class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                           title="Edit">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('smtp.test', $account->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Test Connection">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                        </form>
                                        @if(!$account->is_default)
                                            <form action="{{ route('smtp.set-default', $account->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                        title="Set as Default">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <form action="{{ route('smtp.toggle-active', $account->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="p-2 rounded-lg transition-colors
                                                {{ $account->is_active ? 'text-green-600 hover:bg-green-50' : 'text-red-600 hover:bg-red-50' }}"
                                                title="{{ $account->is_active ? 'Deactivate' : 'Activate' }}">
                                            @if($account->is_active)
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
