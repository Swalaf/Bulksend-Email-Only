<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Choose Your Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
                <p class="text-lg text-gray-600">Choose the plan that fits your email marketing needs</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans ?? [] as $plan)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-8">
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h3>
                                <div class="mb-4">
                                    <span class="text-4xl font-bold text-indigo-600">${{ number_format($plan['price'], 2) }}</span>
                                    <span class="text-gray-500">/month</span>
                                </div>
                                <p class="text-gray-600 mb-6">{{ $plan['description'] }}</p>
                            </div>

                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">{{ number_format($plan['emails_per_month'] ?? 0) }} emails/month</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">SMTP accounts: {{ $plan['smtp_accounts'] ?? 'unlimited' }}</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Analytics & reporting</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">24/7 support</span>
                                </li>
                            </ul>

                            <a href="{{ route('billing.checkout', ['plan_id' => $plan['id']]) }}"
                               class="w-full inline-flex items-center justify-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700">
                                Choose {{ $plan['name'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">Need more emails or custom features?</p>
                <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Contact us for enterprise pricing</a>
            </div>
        </div>
    </div>
</x-app-layout>