<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ __('Create First Campaign') }} - BulkSend</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center px-4 py-12">
            <div class="max-w-2xl w-full">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="ml-2 text-xl font-bold text-gray-800">BulkSend</span>
                    </a>
                </div>

                <!-- Progress Indicator -->
                <div class="mb-8">
                    @php
                        $steps = [
                            'welcome' => ['title' => 'Welcome', 'completed' => true],
                            'business' => ['title' => 'Business', 'completed' => true],
                            'smtp' => ['title' => 'SMTP', 'completed' => true],
                            'campaign' => ['title' => 'Campaign', 'completed' => false],
                        ];
                        $currentStep = 'campaign';
                    @endphp
                    <x-onboarding.progress-indicator :progress="90" :steps="$steps" :currentStep="$currentStep" />
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Create your first campaign</h2>
                    <p class="text-gray-600 mb-8">Get a head start by creating a draft campaign now.</p>

                    @if($hasSmtp)
                        <form action="{{ route('onboarding.store-campaign') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Campaign Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Campaign Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       value="{{ old('name') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                       placeholder="Welcome Email">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Subject <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="subject" 
                                       id="subject"
                                       value="{{ old('subject') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                       placeholder="Welcome to our newsletter!">
                                @error('subject')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">This is what recipients will see in their inbox.</p>
                            </div>

                            <!-- Content -->
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Content
                                </label>
                                <textarea name="content" 
                                          id="content" 
                                          rows="6"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                          placeholder="Write your email content here... (HTML supported)">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">You can edit this later in the campaign editor.</p>
                            </div>

                            <!-- CTA -->
                            <div class="flex flex-col sm:flex-row gap-4 justify-between pt-4">
                                <button type="button" 
                                        onclick="event.preventDefault(); document.getElementById('skip-form').submit();"
                                        class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                    </svg>
                                    Back
                                </button>
                                <div class="flex gap-4">
                                    <button type="button" 
                                            onclick="event.preventDefault(); document.getElementById('skip-form').submit();"
                                            class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                        Skip
                                    </button>
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Create Campaign
                                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form id="skip-form" action="{{ route('onboarding.skip-campaign') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @else
                        <!-- No SMTP Warning -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">No SMTP configured</h3>
                                    <p class="mt-2 text-sm text-yellow-700">You'll need to set up an SMTP account before sending campaigns. You can skip this step for now.</p>
                                    <a href="{{ route('onboarding.smtp') }}" class="mt-3 inline-flex items-center text-sm font-medium text-yellow-800 hover:text-yellow-900">
                                        Set up SMTP now
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-between">
                            <a href="{{ route('onboarding.smtp') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                </svg>
                                Back
                            </a>
                            <button type="button" 
                                    onclick="event.preventDefault(); document.getElementById('skip-form').submit();"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Skip for now
                            </button>
                        </div>
                        <form id="skip-form" action="{{ route('onboarding.skip-campaign') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
