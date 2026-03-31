<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Login') }} - {{ config('app.name', 'BulkSend') }}</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ __('Login') }} - {{ config('app.name', 'BulkSend') }}">
    <meta name="description" content="Sign in to your BulkSend account to manage your email campaigns and marketing automation.">
    <meta name="author" content="BulkSend">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                        accent: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'fade-in': 'fadeIn 0.8s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            animation-delay: 0s;
        }
        .shape:nth-child(2) {
            top: 60%;
            right: 10%;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #764ba2, #f093fb);
            border-radius: 20px;
            transform: rotate(45deg);
            animation-delay: 2s;
        }
        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #f093fb, #667eea);
            border-radius: 50%;
            animation-delay: 4s;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Background with floating shapes -->
    <div class="min-h-screen gradient-bg relative overflow-hidden">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>

        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
            <div class="max-w-md w-full animate-fade-in">

                <!-- Logo -->
                <div class="text-center mb-8 animate-slide-up">
                    <a href="/" class="inline-flex items-center justify-center group">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                            <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="ml-4 text-3xl font-bold text-white tracking-tight">BulkSend</span>
                    </a>
                </div>

                <!-- Login Card -->
                <div class="glass-effect rounded-3xl shadow-2xl p-8 animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
                        <p class="text-white/80">Sign in to continue your journey</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-green-50/10 border border-green-200/20 rounded-xl backdrop-blur-sm">
                            <p class="text-sm text-green-300">{{ session('status') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-white/90">
                                <i class="fas fa-envelope mr-2 text-white/60"></i>Email Address
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:border-white/50 focus:bg-white/20 transition-all duration-300 backdrop-blur-sm"
                                   placeholder="you@example.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-300 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-white/90">
                                <i class="fas fa-lock mr-2 text-white/60"></i>Password
                            </label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   required
                                   class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:border-white/50 focus:bg-white/20 transition-all duration-300 backdrop-blur-sm"
                                   placeholder="••••••••">
                            @error('password')
                                <p class="mt-2 text-sm text-red-300 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center group">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-primary-600 bg-white/10 border-white/20 rounded focus:ring-white/50 focus:ring-offset-0">
                                <span class="ml-3 text-sm text-white/80 group-hover:text-white transition-colors">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-white/80 hover:text-white transition-colors font-medium">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                                class="w-full flex items-center justify-center px-6 py-4 bg-white text-primary-600 font-semibold rounded-xl hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-transparent transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="text-center mt-8">
                        <p class="text-white/80">
                            New to BulkSend?
                            <a href="{{ route('register') }}" class="text-white hover:text-white/80 font-semibold ml-1 transition-colors">
                                Create Account →
                            </a>
                        </p>
                    </div>

                    <!-- Back to Home -->
                    <div class="text-center mt-6">
                        <a href="/" class="inline-flex items-center text-white/60 hover:text-white/80 transition-colors text-sm">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add loading animation to form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
            button.disabled = true;

            // Re-enable after 3 seconds in case of error
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });
    </script>
</body>
</html>
