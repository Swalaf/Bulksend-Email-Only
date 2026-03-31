<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create SMTP Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('vendor.listing-store') }}">
                        @csrf

                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="name" :value="__('Listing Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="pricing_type" :value="__('Pricing Type')" />
                                    <select id="pricing_type" name="pricing_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="per_email" {{ old('pricing_type') === 'per_email' ? 'selected' : '' }}>Per Email</option>
                                        <option value="subscription" {{ old('pricing_type') === 'subscription' ? 'selected' : '' }}>Monthly Subscription</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('pricing_type')" />
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>
                        </div>

                        <!-- SMTP Configuration -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">SMTP Configuration</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="host" :value="__('SMTP Host')" />
                                    <x-text-input id="host" name="host" type="text" class="mt-1 block w-full" :value="old('host')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('host')" />
                                </div>

                                <div>
                                    <x-input-label for="port" :value="__('Port')" />
                                    <x-text-input id="port" name="port" type="number" class="mt-1 block w-full" :value="old('port', 587)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('port')" />
                                </div>

                                <div>
                                    <x-input-label for="encryption" :value="__('Encryption')" />
                                    <select id="encryption" name="encryption" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="tls" {{ old('encryption') === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ old('encryption') === 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('encryption')" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <x-input-label for="from_address" :value="__('From Address')" />
                                    <x-text-input id="from_address" name="from_address" type="email" class="mt-1 block w-full" :value="old('from_address')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('from_address')" />
                                </div>

                                <div>
                                    <x-input-label for="from_name" :value="__('From Name')" />
                                    <x-text-input id="from_name" name="from_name" type="text" class="mt-1 block w-full" :value="old('from_name')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('from_name')" />
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing & Limits</h3>

                            <div id="per-email-pricing" class="pricing-section">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="price_per_email" :value="__('Price per Email ($)')" />
                                        <x-text-input id="price_per_email" name="price_per_email" type="number" step="0.0001" class="mt-1 block w-full" :value="old('price_per_email')" />
                                        <x-input-error class="mt-2" :messages="$errors->get('price_per_email')" />
                                    </div>

                                    <div>
                                        <x-input-label for="free_emails" :value="__('Free Emails')" />
                                        <x-text-input id="free_emails" name="free_emails" type="number" class="mt-1 block w-full" :value="old('free_emails', 0)" />
                                        <x-input-error class="mt-2" :messages="$errors->get('free_emails')" />
                                    </div>
                                </div>
                            </div>

                            <div id="subscription-pricing" class="pricing-section hidden">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="monthly_subscription" :value="__('Monthly Price ($)')" />
                                        <x-text-input id="monthly_subscription" name="monthly_subscription" type="number" step="0.01" class="mt-1 block w-full" :value="old('monthly_subscription')" />
                                        <x-input-error class="mt-2" :messages="$errors->get('monthly_subscription')" />
                                    </div>

                                    <div>
                                        <x-input-label for="included_emails" :value="__('Included Emails')" />
                                        <x-text-input id="included_emails" name="included_emails" type="number" class="mt-1 block w-full" :value="old('included_emails')" />
                                        <x-input-error class="mt-2" :messages="$errors->get('included_emails')" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <x-input-label for="daily_limit" :value="__('Daily Limit')" />
                                    <x-text-input id="daily_limit" name="daily_limit" type="number" class="mt-1 block w-full" :value="old('daily_limit', 1000)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('daily_limit')" />
                                </div>

                                <div>
                                    <x-input-label for="monthly_limit" :value="__('Monthly Limit')" />
                                    <x-text-input id="monthly_limit" name="monthly_limit" type="number" class="mt-1 block w-full" :value="old('monthly_limit', 30000)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('monthly_limit')" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('vendor.dashboard') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Create Listing') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('pricing_type').addEventListener('change', function() {
            const perEmailSection = document.getElementById('per-email-pricing');
            const subscriptionSection = document.getElementById('subscription-pricing');

            if (this.value === 'subscription') {
                perEmailSection.classList.add('hidden');
                subscriptionSection.classList.remove('hidden');
            } else {
                subscriptionSection.classList.add('hidden');
                perEmailSection.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>