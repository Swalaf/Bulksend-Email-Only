<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ __('Business Details') }} - BulkSend</title>
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
                            'business' => ['title' => 'Business', 'completed' => false],
                            'smtp' => ['title' => 'SMTP', 'completed' => false],
                            'campaign' => ['title' => 'Campaign', 'completed' => false],
                        ];
                        $currentStep = 'business';
                    @endphp
                    <x-onboarding.progress-indicator :progress="50" :steps="$steps" :currentStep="$currentStep" />
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tell us about your business</h2>
                    <p class="text-gray-600 mb-8">This helps us personalize your experience and improve deliverability.</p>

                    <form action="{{ route('onboarding.store-business') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Business Name
                            </label>
                            <input type="text"
                                   name="business_name"
                                   id="business_name"
                                   value="{{ old('business_name', $business['name'] ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="Your Company Name">
                            @error('business_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">This will appear as the sender name in your emails.</p>
                        </div>

                        <!-- Business Description -->
                        <div>
                            <label for="business_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Business Description
                            </label>
                            <textarea name="business_description" 
                                      id="business_description" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                      placeholder="Brief description of your business">{{ old('business_description', $business['description'] ?? '') }}</textarea>
                            @error('business_description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Website -->
                        <div>
                            <label for="business_website" class="block text-sm font-medium text-gray-700 mb-2">
                                Website
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">https://</span>
                                <input type="url" 
                                       name="business_website" 
                                       id="business_website"
                                       value="{{ old('business_website', $business['website'] ?? '') }}"
                                       class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                       placeholder="www.yourcompany.com">
                            </div>
                            @error('business_website')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CTA -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-between pt-4">
                            <a href="{{ route('onboarding.welcome') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                </svg>
                                Back
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Continue
                                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
