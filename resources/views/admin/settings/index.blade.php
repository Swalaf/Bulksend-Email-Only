@extends('layouts.admin')

@section('title', 'Settings')
@section('header', 'Settings')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <nav class="p-2">
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.settings.index') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                        <i class="fas fa-cog w-5"></i>
                        <span class="ml-3">General</span>
                    </a>
                    <a href="{{ route('admin.settings.appearance') }}" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.settings.appearance') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                        <i class="fas fa-palette w-5"></i>
                        <span class="ml-3">Appearance</span>
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.settings.email') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                        <i class="fas fa-envelope w-5"></i>
                        <span class="ml-3">Email</span>
                    </a>
                    <a href="{{ route('admin.settings.logs') }}" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('admin.settings.logs*') ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : '' }}">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-3">System Logs</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">General Settings</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Configure basic application settings</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Site Information -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white uppercase tracking-wider mb-4">Site Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Name</label>
                                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Email</label>
                                    <input type="email" name="site_email" value="{{ $settings['site_email'] ?? '' }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Support Email</label>
                                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </div>
                        </div>

                        <!-- Commission Settings -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white uppercase tracking-wider mb-4">Commission Settings</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Platform Commission Rate (%)</label>
                                    <input type="number" name="commission_rate" value="{{ $settings['commission_rate'] ?? 10 }}" min="0" max="100" step="0.1"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                    <p class="text-xs text-gray-500 mt-1">Commission taken from each transaction</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vendor Commission Rate (%)</label>
                                    <input type="number" name="vendor_commission_rate" value="{{ $settings['vendor_commission_rate'] ?? 70 }}" min="0" max="100" step="0.1"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                    <p class="text-xs text-gray-500 mt-1">Percentage vendors receive from sales</p>
                                </div>
                            </div>
                        </div>

                        <!-- System Settings -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white uppercase tracking-wider mb-4">System Settings</h4>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" 
                                           {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    <label for="maintenance_mode" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Maintenance Mode</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="registration_enabled" id="registration_enabled" value="1" 
                                           {{ ($settings['registration_enabled'] ?? true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    <label for="registration_enabled" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable User Registration</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="email_verification_required" id="email_verification_required" value="1" 
                                           {{ ($settings['email_verification_required'] ?? true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    <label for="email_verification_required" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Require Email Verification</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg">
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                            <i class="fas fa-save mr-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
