<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ __('Welcome to BulkSend') }}</title>
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
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome to BulkSend!</h1>
                    <p class="mt-2 text-gray-600">Let's get your email marketing up and running</p>
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
                        $currentStep = 'welcome';
                    @endphp
                    <x-onboarding.progress-indicator :progress="25" :steps="$steps" :currentStep="$currentStep" />
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <!-- Welcome Message -->
                    <div class="text-center mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Hi, {{ Auth::user()->name }}! 👋</h2>
                        <p class="text-gray-600">We're excited to have you on board. This quick setup will help you start sending emails in just a few minutes.</p>
                    </div>

                    <!-- Features -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="text-center p-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-900">Send Campaigns</h3>
                            <p class="text-sm text-gray-500 mt-1">Create and send email campaigns to your subscribers</p>
                        </div>
                        <div class="text-center p-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-900">Track Analytics</h3>
                            <p class="text-sm text-gray-500 mt-1">Monitor opens, clicks, and engagement metrics</p>
                        </div>
                        <div class="text-center p-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-900">Manage Lists</h3>
                            <p class="text-sm text-gray-500 mt-1">Organize and segment your subscribers</p>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('onboarding.business') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            Get Started
                            <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="{{ route('onboarding.business') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            Skip for now
                        </a>
                    </div>

                    <!-- Tooltip -->
                    <p class="text-center text-sm text-gray-400 mt-6">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            This takes less than 2 minutes
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
