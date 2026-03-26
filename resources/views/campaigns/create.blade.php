@extends('layouts.app')

@section('title', 'Create Campaign')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create Campaign</h1>
            <a href="{{ route('campaigns.index') }}" class="text-gray-500 hover:text-gray-700">
                ← Back to Campaigns
            </a>
        </div>

        <form method="POST" action="{{ route('campaigns.store') }}" class="space-y-6">
            @csrf

            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium mb-4">Campaign Details</h2>
                
                <div class="grid gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Campaign Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="e.g., Summer Newsletter">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Email Subject</label>
                        <input type="text" name="subject" id="subject" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="e.g., Don't miss our summer sale!">
                        @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="smtp_account_id" class="block text-sm font-medium text-gray-700 mb-1">SMTP Account</label>
                        <select name="smtp_account_id" id="smtp_account_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select SMTP Account</option>
                            @foreach($smtpAccounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->from_address }})</option>
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
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm"
                            placeholder="<html>...</html>"></textarea>
                        @error('html_content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            Use {{ '{{email}}' }} for subscriber email, {{ '{{name}}' }} for subscriber name.
                            Links will be automatically tracked.
                        </p>
                    </div>

                    <div>
                        <label for="plain_text_content" class="block text-sm font-medium text-gray-700 mb-1">Plain Text Version (Optional)</label>
                        <textarea name="plain_text_content" id="plain_text_content" rows="4"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Plain text version for email clients that don't support HTML"></textarea>
                    </div>
                </div>
            </div>

            <!-- Scheduling -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium mb-4">Scheduling</h2>
                
                <div class="grid gap-6">
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Schedule Send (Optional)</label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to save as draft</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="batch_size" class="block text-sm font-medium text-gray-700 mb-1">Batch Size</label>
                            <input type="number" name="batch_size" id="batch_size" value="100" min="1" max="500"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Emails per batch</p>
                        </div>

                        <div>
                            <label for="batch_delay" class="block text-sm font-medium text-gray-700 mb-1">Batch Delay (seconds)</label>
                            <input type="number" name="batch_delay" id="batch_delay" value="5" min="0" max="300"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Delay between batches</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('campaigns.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Create Campaign
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
