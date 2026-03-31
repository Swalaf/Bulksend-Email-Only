<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BulkSend') }} - {{ $title ?? 'Dashboard' }}</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ config('app.name', 'BulkSend') }} - {{ $title ?? 'Dashboard' }}">
    <meta name="description" content="Professional email marketing platform with advanced analytics, automation, and campaign management.">
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
                        'slide-in-left': 'slideInLeft 0.5s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
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
                        },
                        slideInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
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
        .sidebar-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
        }
        .nav-item-active {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.1), rgba(20, 184, 166, 0.1));
            border-right: 3px solid #0284c7;
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
            opacity: 0.05;
            animation: float 8s ease-in-out infinite;
        }
        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
        }
        .shape:nth-child(2) {
            top: 70%;
            right: 15%;
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #764ba2, #f093fb);
            border-radius: 20px;
            transform: rotate(45deg);
        }
        .shape:nth-child(3) {
            bottom: 30%;
            left: 70%;
            width: 30px;
            height: 30px;
            background: linear-gradient(45deg, #f093fb, #667eea);
            border-radius: 50%;
        }
    </style>
</head>
<body class="font-sans antialiased bg-primary-50">
    <!-- Background decoration -->
    <div class="fixed inset-0 gradient-bg opacity-5 pointer-events-none"></div>
    <div class="fixed inset-0 floating-shapes pointer-events-none"></div>

    <div class="relative min-h-screen flex">
        <!-- Sidebar -->
        <aside class="hidden lg:flex lg:flex-shrink-0">
            <div class="sidebar-glass w-64 min-h-screen relative">
                @include('layouts.sidebar')
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navigation -->
            <header class="glass-effect border-b border-white/20 relative z-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <!-- Mobile menu button -->
                        <button class="lg:hidden text-primary-700 hover:text-primary-900 transition-colors" onclick="toggleMobileMenu()">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Page title -->
                        <div class="flex-1 lg:ml-0">
                            @if (isset($header))
                                <div class="animate-slide-up">
                                    {{ $header }}
                                </div>
                            @endif
                        </div>

                        <!-- User menu -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="text-primary-700 hover:text-primary-900 transition-colors relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                            </button>

                            <!-- User dropdown -->
                            @auth
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="flex items-center space-x-2 text-primary-700 hover:text-primary-900 transition-colors">
                                        <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                        <span class="hidden sm:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 glass-effect rounded-xl shadow-lg py-2 z-50">
                                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-primary-700 hover:bg-primary-50 transition-colors">
                                            <i class="fas fa-user mr-3"></i>Profile
                                        </a>
                                        <a href="{{ route('billing.index') }}" class="flex items-center px-4 py-2 text-sm text-primary-700 hover:bg-primary-50 transition-colors">
                                            <i class="fas fa-credit-card mr-3"></i>Billing
                                        </a>
                                        <hr class="my-2 border-primary-200">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile sidebar overlay -->
            <div id="mobile-sidebar" class="fixed inset-0 z-40 lg:hidden hidden">
                <div class="fixed inset-0 bg-black bg-opacity-25" onclick="toggleMobileMenu()"></div>
                <div class="fixed inset-y-0 left-0 w-64 sidebar-glass transform transition-transform duration-300">
                    @include('layouts.sidebar')
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 animate-fade-in">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('mobile-sidebar');
            sidebar.classList.toggle('hidden');
        }

        // Auto-hide mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                document.getElementById('mobile-sidebar').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
