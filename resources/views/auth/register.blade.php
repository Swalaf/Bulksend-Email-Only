<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ __('Register') }} - BulkSend</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex items-center justify-center px-4 py-12">
            <div class="max-w-md w-full">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex items-center justify-center">
                        <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="ml-3 text-2xl font-bold text-gray-800">BulkSend</span>
                    </a>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Create your account</h2>
                    <p class="text-gray-600 mb-8">Start sending emails in minutes</p>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="John Doe">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="you@example.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
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

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="••••••••">
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                I want to:
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative">
                                    <input type="radio" name="role" value="user" class="peer sr-only" {{ old('role') == 'user' ? 'checked' : '' }}>
                                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-all">
                                        <svg class="w-6 h-6 text-gray-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <p class="text-sm font-medium text-center">Send Emails</p>
                                        <p class="text-xs text-gray-500 text-center mt-1">Use for my business</p>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="role" value="vendor" class="peer sr-only" {{ old('role') == 'vendor' ? 'checked' : '' }}>
                                    <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-all">
                                        <svg class="w-6 h-6 text-gray-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-sm font-medium text-center">Resell</p>
                                        <p class="text-xs text-gray-500 text-center mt-1">Start an email business</p>
                                    </div>
                                </label>
                            </div>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms -->
                        <div class="flex items-start">
                            <input type="checkbox" name="terms" id="terms" required class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="terms" class="ml-2 text-sm text-gray-600">
                                I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-700">Terms of Service</a> and <a href="#" class="text-indigo-600 hover:text-indigo-700">Privacy Policy</a>
                            </label>
                        </div>

                        <!-- Submit -->
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Create Account
                        </button>
                    </form>

                    <!-- Login Link -->
                    <p class="text-center text-sm text-gray-600 mt-8">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
