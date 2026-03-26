@extends('layouts.app')

@section('title', 'Edit Campaign')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Campaign</h1>
            <a href="{{ route('campaigns.show', $campaign->id) }}" class="text-gray-500 hover:text-gray-700">
                ← Back to Campaign
            </a>
        </div>

        <form method="POST" action="{{ route('campaigns.update', $campaign->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium mb-4">Campaign Details</h2>
                
                <div class="grid gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Campaign Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $campaign->name) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Email Subject</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject', $campaign->subject) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="smtp_account_id" class="block text-sm font-medium text-gray-700 mb-1">SMTP Account</label>
                        <select name="smtp_account_id" id="smtp_account_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($smtpAccounts as $account)
                            <option value="{{ $account->id }}" {{ $campaign->smtp_account_id == $account->id ? 'selected' : '' }}>
                                {{ $account->name }} ({{ $account->from_address }})
                            </option>
                            @endforeach
                        </select>
                        @error('smtp_account_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Email Content -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium mb-4">Email Content</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="html_content" class="block text-sm font-medium text-gray-700 mb-1">HTML Content</label>
                        <textarea name="html_content" id="html_content" rows="12" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm">{{ old('html_content', $campaign->html_content) }}</textarea>
                        @error('html_content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="plain_text_content" class="block text-sm font-medium text-gray-700 mb-1">Plain Text Version</label>
                        <textarea name="plain_text_content" id="plain_text_content" rows="4"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('plain_text_content', $campaign->plain_text_content) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Scheduling -->
            @if($campaign->isDraft())
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium mb-4">Scheduling</h2>
                
                <div>
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Schedule Send</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" 
                        value="{{ old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep as draft</p>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('campaigns.show', $campaign->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
