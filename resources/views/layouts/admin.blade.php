<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'BulkSend') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
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
                        }
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col">
            <div class="h-16 flex items-center justify-center border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-primary-600">
                    <i class="fas fa-paper-plane mr-2"></i>BulkSend Admin
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-th-large w-5"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="pt-4 pb-2 px-4">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">User Management</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-users w-5"></i>
                            <span class="ml-3">Users</span>
                            @if(\App\Models\User::where('email_verified_at', null)->count() > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                    {{ \App\Models\User::where('email_verified_at', null)->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <li class="pt-4 pb-2 px-4">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Marketplace</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.vendors.index') }}" 
                           class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.vendors.*') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-store w-5"></i>
                            <span class="ml-3">Vendors</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.vendors.listings') }}" 
                           class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.vendors.listings') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-tags w-5"></i>
                            <span class="ml-3">SMTP Listings</span>
                        </a>
                    </li>
                    
                    <li class="pt-4 pb-2 px-4">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">SMTP</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.smtp.index') }}" 
                           class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.smtp.*') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-server w-5"></i>
                            <span class="ml-3">All SMTP Accounts</span>
                        </a>
                    </li>
                    
                    <li class="pt-4 pb-2 px-4">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">System</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.index') }}" 
                           class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.settings.*') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-cog w-5"></i>
                            <span class="ml-3">Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="flex items-center text-gray-700 dark:text-gray-200 hover:text-primary-600">
                    <i class="fas fa-arrow-left w-5"></i>
                    <span class="ml-3">Back to App</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-white">@yield('header', 'Dashboard')</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <button onclick="toggleDarkMode()" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:inline"></i>
                    </button>
                    
                    <div class="relative">
                        <button class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <i class="fas fa-bell"></i>
                        </button>
                    </div>

                    <div class="flex items-center space-x-2">
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0ea5e9&color=fff" alt="">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @stack('scripts')
</body>
</html>
