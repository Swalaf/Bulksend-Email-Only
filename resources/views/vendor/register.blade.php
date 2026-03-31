<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Become a Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Join Our Vendor Marketplace</h3>
                        <p class="text-gray-600">Sell your SMTP accounts and earn money. Get started by creating your vendor profile.</p>
                    </div>

                    <form method="POST" action="{{ route('vendor.register') }}">
                        @csrf

                        <!-- Shop Name -->
                        <div class="mb-4">
                            <x-input-label for="shop_name" :value="__('Shop Name')" />
                            <x-text-input id="shop_name" name="shop_name" type="text" class="mt-1 block w-full" :value="old('shop_name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('shop_name')" />
                            <p class="mt-1 text-sm text-gray-500">Your unique shop name on the marketplace</p>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Shop Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            <p class="mt-1 text-sm text-gray-500">Describe your services and what makes you unique</p>
                        </div>

                        <!-- Website -->
                        <div class="mb-6">
                            <x-input-label for="website" :value="__('Website (Optional)')" />
                            <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website')" placeholder="https://yourwebsite.com" />
                            <x-input-error class="mt-2" :messages="$errors->get('website')" />
                        </div>

                        <!-- Terms -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="terms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" required>
                                <span class="ml-2 text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">vendor terms</a> and <a href="#" class="text-indigo-600 hover:text-indigo-500">marketplace policies</a>
                                </span>
                            </label>
                            @error('terms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Apply to Become Vendor') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-800 mb-2">What happens next?</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Your application will be reviewed by our team</li>
                            <li>• You'll receive an email notification within 24-48 hours</li>
                            <li>• Once approved, you can start creating SMTP listings</li>
                            <li>• Earn commission on every sale (default 10%)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>