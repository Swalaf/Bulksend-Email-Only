<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Purchase SMTP Account
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Complete Your Purchase</h3>

                    <!-- Listing Summary -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="font-medium text-gray-900">{{ $listing->name }}</h4>
                        <p class="text-sm text-gray-600">{{ $listing->vendor->shop_name }}</p>
                        <div class="mt-2 flex justify-between items-center">
                            <span class="text-2xl font-bold text-indigo-600">{{ $listing->getFormattedPrice() }}</span>
                            <span class="text-sm text-gray-500">{{ $listing->pricing_type === 'subscription' ? 'Monthly' : 'One-time' }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('marketplace.purchase', $listing->id) }}">
                        @csrf

                        <!-- SMTP Credentials -->
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">SMTP Account Details</h4>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="username" :value="__('SMTP Username')" />
                                    <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('username')" />
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('SMTP Password')" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                </div>

                                <div>
                                    <x-input-label for="from_address" :value="__('From Email Address')" />
                                    <x-text-input id="from_address" name="from_address" type="email" class="mt-1 block w-full" :value="old('from_address', $listing->from_address)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('from_address')" />
                                    <p class="mt-1 text-sm text-gray-500">The email address that will appear as the sender</p>
                                </div>

                                <div>
                                    <x-input-label for="from_name" :value="__('From Name (Optional)')" />
                                    <x-text-input id="from_name" name="from_name" type="text" class="mt-1 block w-full" :value="old('from_name', $listing->from_name)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('from_name')" />
                                </div>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input type="checkbox" name="terms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mt-1" required>
                                <span class="ml-2 text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">terms of service</a> and understand that this purchase is for email marketing purposes only.
                                </span>
                            </label>
                            @error('terms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Purchase Button -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('marketplace.show', $listing->id) }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Complete Purchase
                                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.5.728 1.5h9.836c.912 0 1.358-.87.728-1.5L13 7m5 4v1m0-1V7m-5 8h.01M11 11h1v1h-1z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>