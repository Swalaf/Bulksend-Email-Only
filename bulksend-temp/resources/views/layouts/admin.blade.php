<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'BulkSend') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        document.documentElement.classList.add('dark');
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

    @stack('scripts')
</body>
</html>
