<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ __('SMTP Setup') }} - BulkSend</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center px-4 py-12">
            <div class="max-w-3xl w-full">
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
                            'smtp' => ['title' => 'SMTP', 'completed' => false],
                            'campaign' => ['title' => 'Campaign', 'completed' => false],
                        ];
                        $currentStep = 'smtp';
                    @endphp
                    <x-onboarding.progress-indicator :progress="75" :steps="$steps" :currentStep="$currentStep" />
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Set up your SMTP</h2>
                    <p class="text-gray-600 mb-8">Configure your email sending server to start sending campaigns.</p>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <button onclick="showTab('custom')" 
                                    class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Custom SMTP
                            </button>
                            <button onclick="showTab('marketplace')" 
                                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                SMTP Marketplace
                            </button>
                        </nav>
                    </div>

                    <!-- Custom SMTP Form -->
                    <div id="custom-tab">
                        <form action="{{ route('onboarding.store-smtp') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- SMTP Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Configuration Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name', 'Default') }}"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                           placeholder="My SMTP">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Host -->
                                <div>
                                    <label for="host" class="block text-sm font-medium text-gray-700 mb-2">
                                        SMTP Host <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="host" 
                                           id="host"
                                           value="{{ old('host') }}"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                           placeholder="smtp.example.com">
                                    @error('host')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Port -->
                                <div>
                                    <label for="port" class="block text-sm font-medium text-gray-700 mb-2">
                                        Port <span class="text-red-500">*</span>
                                    </label>
                                    <select name="port" id="port" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <option value="587" {{ old('port') == '587' ? 'selected' : '' }}>587 (TLS)</option>
                                        <option value="465" {{ old('port') == '465' ? 'selected' : '' }}>465 (SSL)</option>
                                        <option value="25" {{ old('port') == '25' ? 'selected' : '' }}>25</option>
                                    </select>
                                    @error('port')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Encryption -->
                                <div>
                                    <label for="encryption" class="block text-sm font-medium text-gray-700 mb-2">
                                        Encryption
                                    </label>
                                    <select name="encryption" id="encryption" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <option value="tls" {{ old('encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                    @error('encryption')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Username -->
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                        Username <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="username" 
                                           id="username"
                                           value="{{ old('username') }}"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                           placeholder="user@example.com">
                                    @error('username')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           id="password"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                           placeholder="••••••••">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- From Address -->
                                <div>
                                    <label for="from_address" class="block text-sm font-medium text-gray-700 mb-2">
                                        From Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           name="from_address" 
                                           id="from_address"
                                           value="{{ old('from_address') }}"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                           placeholder="noreply@yourdomain.com">
                                    @error('from_address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- From Name -->
                                <div>
                                    <label for="from_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        From Name
                                    </label>
                                    <input type="text" 
                                           name="from_name" 
                                           id="from_name"
                                           value="{{ old('from_name', Auth::user()->business_name ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                           placeholder="Your Company">
                                    @error('from_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- CTA -->
                            <div class="flex flex-col sm:flex-row gap-4 justify-between pt-4">
                                <a href="{{ route('onboarding.business') }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                    </svg>
                                    Back
                                </a>
                                <div class="flex gap-4">
                                    <button type="button" 
                                            onclick="event.preventDefault(); document.getElementById('skip-form').submit();"
                                            class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                        Skip
                                    </button>
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Save & Continue
                                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form id="skip-form" action="{{ route('onboarding.skip-smtp') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>

                    <!-- Marketplace Tab -->
                    <div id="marketplace-tab" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Mailgun -->
                            <div class="border border-gray-200 rounded-xl p-4 hover:border-indigo-300 transition-colors cursor-pointer">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <span class="text-red-600 font-bold">M</span>
                                    </div>
                                    <h3 class="ml-3 font-medium text-gray-900">Mailgun</h3>
                                </div>
                                <p class="text-sm text-gray-500">Reliable email delivery service with powerful APIs.</p>
                                <a href="#" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700">Learn more →</a>
                            </div>

                            <!-- SendGrid -->
                            <div class="border border-gray-200 rounded-xl p-4 hover:border-indigo-300 transition-colors cursor-pointer">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600 font-bold">S</span>
                                    </div>
                                    <h3 class="ml-3 font-medium text-gray-900">SendGrid</h3>
                                </div>
                                <p class="text-sm text-gray-500">Twilio's email platform for developers.</p>
                                <a href="#" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700">Learn more →</a>
                            </div>

                            <!-- Amazon SES -->
                            <div class="border border-gray-200 rounded-xl p-4 hover:border-indigo-300 transition-colors cursor-pointer">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <span class="text-orange-600 font-bold">S</span>
                                    </div>
                                    <h3 class="ml-3 font-medium text-gray-900">Amazon SES</h3>
                                </div>
                                <p class="text-sm text-gray-500">Scalable email service from AWS.</p>
                                <a href="#" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700">Learn more →</a>
                            </div>

                            <!-- Postmark -->
                            <div class="border border-gray-200 rounded-xl p-4 hover:border-indigo-300 transition-colors cursor-pointer">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <span class="text-green-600 font-bold">P</span>
                                    </div>
                                    <h3 class="ml-3 font-medium text-gray-900">Postmark</h3>
                                </div>
                                <p class="text-sm text-gray-500">Email delivery for developers who care about deliverability.</p>
                                <a href="#" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700">Learn more →</a>
                            </div>
                        </div>

                        <p class="text-center text-sm text-gray-500 mt-6">
                            Need help? <a href="#" class="text-indigo-600 hover:text-indigo-700">Check our SMTP setup guide</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showTab(tab) {
                document.getElementById('custom-tab').classList.add('hidden');
                document.getElementById('marketplace-tab').classList.add('hidden');
                document.getElementById(tab + '-tab').classList.remove('hidden');
            }
        </script>
    </body>
</html>
