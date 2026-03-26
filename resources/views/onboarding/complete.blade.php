<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ __('All Done!') }} - BulkSend</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center px-4 py-12">
            <div class="max-w-2xl w-full">
                <!-- Success Animation -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6 animate-bounce">
                        <svg class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">You're all set! 🎉</h1>
                    <p class="mt-2 text-gray-600">Welcome to BulkSend, {{ $user->name }}!</p>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <!-- Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600">{{ $user->business_name ?? 'Your Business' }}</p>
                            <p class="text-xs text-gray-400">Business Profile</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600">{{ $user->smtpAccounts->count() }} SMTP</p>
                            <p class="text-xs text-gray-400">Configured</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600">{{ $user->campaigns->count() }} Campaign</p>
                            <p class="text-xs text-gray-400">Created</p>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="space-y-4 mb-8">
                        <h3 class="font-semibold text-gray-900">What you can do next:</h3>
                        
                        <a href="{{ route('campaigns.create') }}" class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-colors group">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Create a new campaign</p>
                                <p class="text-sm text-gray-500">Send emails to your subscribers</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('subscribers.create') }}" class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-colors group">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Add subscribers</p>
                                <p class="text-sm text-gray-500">Build your email list</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('smtp.create') }}" class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-colors group">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Configure more SMTP</p>
                                <p class="text-sm text-gray-500">Add additional email providers</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <!-- CTA -->
                    <div class="text-center">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            Go to Dashboard
                            <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Help -->
                <p class="text-center text-sm text-gray-500 mt-8">
                    Need help? <a href="#" class="text-indigo-600 hover:text-indigo-700">Check our documentation</a> or <a href="#" class="text-indigo-600 hover:text-indigo-700">contact support</a>
                </p>
            </div>
        </div>
    </body>
</html>
