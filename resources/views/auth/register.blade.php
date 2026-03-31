<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Register') }} - {{ config('app.name', 'BulkSend') }}</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ __('Register') }} - {{ config('app.name', 'BulkSend') }}">
    <meta name="description" content="Create your BulkSend account and start sending professional email campaigns. Join thousands of businesses using our powerful email marketing platform.">
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
                        'slide-up-delayed': 'slideUp 0.6s ease-out 0.2s both',
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
            top: 15%;
            left: 15%;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            animation-delay: 0s;
        }
        .shape:nth-child(2) {
            top: 70%;
            right: 15%;
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, #764ba2, #f093fb);
            border-radius: 20px;
            transform: rotate(45deg);
            animation-delay: 3s;
        }
        .shape:nth-child(3) {
            bottom: 25%;
            left: 25%;
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #f093fb, #667eea);
            border-radius: 50%;
            animation-delay: 6s;
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

        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-8">
            <div class="max-w-lg w-full animate-fade-in">

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

                <!-- Registration Card -->
                <div class="glass-effect rounded-3xl shadow-2xl p-8 animate-slide-up-delayed">

                    <!-- Progress Indicator -->
                    <div class="flex justify-center mb-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">1</span>
                            </div>
                            <div class="w-12 h-1 bg-white/30 rounded"></div>
                            <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">2</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-white mb-2">Join BulkSend</h1>
                        <p class="text-white/80">Create your account and start sending professional emails</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-white/90">
                                <i class="fas fa-user mr-2 text-white/60"></i>Full Name
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:border-white/50 focus:bg-white/20 transition-all duration-300 backdrop-blur-sm"
                                   placeholder="John Doe">
                            @error('name')
                                <p class="mt-2 text-sm text-red-300 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

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

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-white/90">
                                <i class="fas fa-lock mr-2 text-white/60"></i>Confirm Password
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   required
                                   class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:border-white/50 focus:bg-white/20 transition-all duration-300 backdrop-blur-sm"
                                   placeholder="••••••••">
                        </div>

                        <!-- Role Selection -->
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-white/90">
                                <i class="fas fa-briefcase mr-2 text-white/60"></i>What brings you to BulkSend?
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="role" value="user" class="peer sr-only" {{ old('role', 'user') == 'user' ? 'checked' : '' }}>
                                    <div class="p-5 bg-white/5 border-2 border-white/20 rounded-xl backdrop-blur-sm transition-all duration-300 group-hover:bg-white/10 peer-checked:border-accent-400 peer-checked:bg-accent-500/20 peer-checked:border-2">
                                        <div class="flex items-center justify-center mb-3">
                                            <div class="w-12 h-12 bg-accent-500/20 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-white font-medium text-center text-sm mb-1">Send Emails</p>
                                        <p class="text-white/60 text-center text-xs">Perfect for businesses & marketers</p>
                                    </div>
                                </label>
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="role" value="vendor" class="peer sr-only" {{ old('role') == 'vendor' ? 'checked' : '' }}>
                                    <div class="p-5 bg-white/5 border-2 border-white/20 rounded-xl backdrop-blur-sm transition-all duration-300 group-hover:bg-white/10 peer-checked:border-primary-400 peer-checked:bg-primary-500/20 peer-checked:border-2">
                                        <div class="flex items-center justify-center mb-3">
                                            <div class="w-12 h-12 bg-primary-500/20 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-white font-medium text-center text-sm mb-1">Become a Vendor</p>
                                        <p class="text-white/60 text-center text-xs">Sell email services & earn</p>
                                    </div>
                                </label>
                            </div>
                            @error('role')
                                <p class="mt-2 text-sm text-red-300 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Terms -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" name="terms" id="terms" required class="mt-1 w-4 h-4 text-primary-600 bg-white/10 border-white/20 rounded focus:ring-white/50 focus:ring-offset-0">
                            <label for="terms" class="text-sm text-white/80 leading-relaxed">
                                I agree to the <a href="#" class="text-accent-400 hover:text-accent-300 underline transition-colors">Terms of Service</a> and <a href="#" class="text-accent-400 hover:text-accent-300 underline transition-colors">Privacy Policy</a>
                            </label>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                                class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-primary-500 to-accent-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-accent-600 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-transparent transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-rocket mr-2"></i>
                            Create My Account
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-8">
                        <p class="text-white/80">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-white hover:text-white/80 font-semibold ml-1 transition-colors">
                                Sign In Here →
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
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
            button.disabled = true;

            // Re-enable after 3 seconds in case of error
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });

        // Enhanced role selection interaction
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Add subtle animation or feedback
                this.closest('label').style.transform = 'scale(1.02)';
                setTimeout(() => {
                    this.closest('label').style.transform = '';
                }, 200);
            });
        });
    </script>
</body>
</html>
