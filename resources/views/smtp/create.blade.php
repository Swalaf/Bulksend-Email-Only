<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add SMTP Account') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <form action="{{ route('smtp.store') }}" method="POST" id="smtp-form">
                    @csrf

                    <!-- Account Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Account Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                               placeholder="e.g., Gmail, Company SMTP">
                        <p class="mt-1 text-sm text-gray-500">A friendly name to identify this account.</p>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SMTP Server Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Host -->
                        <div>
                            <label for="host" class="block text-sm font-medium text-gray-700 mb-2">
                                SMTP Host <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="host" 
                                   id="host"
                                   value="{{ old('host') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="smtp.example.com">
                            @error('host')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Port -->
                        <div>
                            <label for="port" class="block text-sm font-medium text-gray-700 mb-2">
                                Port <span class="text-red-500">*</span>
                            </label>
                            <select name="port" id="port" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="587" {{ old('port') == '587' ? 'selected' : '' }}>587 (TLS - Recommended)</option>
                                <option value="465" {{ old('port') == '465' ? 'selected' : '' }}>465 (SSL)</option>
                                <option value="25" {{ old('port') == '25' ? 'selected' : '' }}>25 (No Encryption)</option>
                                <option value="2525" {{ old('port') == '2525' ? 'selected' : '' }}>2525 (Alternate)</option>
                            </select>
                            @error('port')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Encryption -->
                    <div class="mb-6">
                        <label for="encryption" class="block text-sm font-medium text-gray-700 mb-2">
                            Encryption <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="encryption" value="tls" {{ old('encryption', 'tls') == 'tls' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">TLS (Recommended)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="encryption" value="ssl" {{ old('encryption') == 'ssl' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">SSL</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="encryption" value="none" {{ old('encryption') == 'none' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">None</span>
                            </label>
                        </div>
                        @error('encryption')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Credentials -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="username" 
                                   id="username"
                                   value="{{ old('username') }}"
                                   required
                                   autocomplete="off"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="Full email address or username">
                            @error('username')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password / App Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   required
                                   autocomplete="new-password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="••••••••••••">
                            <p class="mt-1 text-sm text-gray-500">For Gmail, use an App Password.</p>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Sender Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- From Address -->
                        <div>
                            <label for="from_address" class="block text-sm font-medium text-gray-700 mb-2">
                                From Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="from_address" 
                                   id="from_address"
                                   value="{{ old('from_address') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="noreply@yourdomain.com">
                            @error('from_address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- From Name -->
                        <div>
                            <label for="from_name" class="block text-sm font-medium text-gray-700 mb-2">
                                From Name
                            </label>
                            <input type="text" 
                                   name="from_name" 
                                   id="from_name"
                                   value="{{ old('from_name', Auth::user()->business_name ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="Your Company Name">
                            @error('from_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Limits -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="daily_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                Daily Email Limit
                            </label>
                            <input type="number" 
                                   name="daily_limit" 
                                   id="daily_limit"
                                   value="{{ old('daily_limit', 500) }}"
                                   min="1"
                                   max="100000"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label for="monthly_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                Monthly Email Limit
                            </label>
                            <input type="number" 
                                   name="monthly_limit" 
                                   id="monthly_limit"
                                   value="{{ old('monthly_limit', 10000) }}"
                                   min="1"
                                   max="1000000"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                    </div>

                    <!-- Default Toggle -->
                    <div class="mb-8">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Set as default SMTP account</span>
                        </label>
                    </div>

                    <!-- Test Connection -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">Test Connection</h4>
                                <p class="text-sm text-gray-500">Test your SMTP settings before saving</p>
                            </div>
                            <button type="button" 
                                    id="test-connection"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2 hidden" id="test-loading" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg class="w-5 h-5 mr-2" id="test-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span id="test-text">Test Connection</span>
                            </button>
                        </div>
                        <div id="test-result" class="mt-4 hidden">
                            <div class="p-3 rounded-lg" id="test-result-content"></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('smtp.index') }}" 
                           class="px-6 py-3 text-gray-700 font-medium rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save SMTP Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h4 class="font-medium text-blue-900 mb-2">💡 Tips for SMTP Setup</h4>
                <ul class="text-sm text-blue-800 space-y-2">
                    <li>• <strong>Gmail:</strong> Use App Password (enable 2FA first) or use OAuth2</li>
                    <li>• <strong>Office 365:</strong> Use port 587 with TLS</li>
                    <li>• <strong>Mailgun/SendGrid:</strong> Use API credentials from their dashboards</li>
                    <li>• <strong>Self-hosted:</strong> Check your mail server documentation</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const testBtn = document.getElementById('test-connection');
            const testResult = document.getElementById('test-result');
            const testResultContent = document.getElementById('test-result-content');
            const testLoading = document.getElementById('test-loading');
            const testIcon = document.getElementById('test-icon');
            const testText = document.getElementById('test-text');

            testBtn.addEventListener('click', function() {
                const host = document.getElementById('host').value;
                const port = document.getElementById('port').value;
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                const encryption = document.querySelector('input[name="encryption"]:checked').value;

                if (!host || !username || !password) {
                    alert('Please fill in all SMTP fields before testing.');
                    return;
                }

                // Show loading
                testBtn.disabled = true;
                testLoading.classList.remove('hidden');
                testIcon.classList.add('hidden');
                testText.textContent = 'Testing...';
                testResult.classList.add('hidden');

                // Make request
                fetch('{{ route("smtp.test-config") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        host, port, username, password, encryption
                    })
                })
                .then(response => response.json())
                .then(data => {
                    testResult.classList.remove('hidden');
                    
                    if (data.success) {
                        testResultContent.className = 'p-3 rounded-lg bg-green-100 text-green-800';
                        testResultContent.innerHTML = '<strong>✓ Connection Successful!</strong><br>' + data.message;
                    } else {
                        testResultContent.className = 'p-3 rounded-lg bg-red-100 text-red-800';
                        testResultContent.innerHTML = '<strong>✗ Connection Failed</strong><br>' + data.message;
                    }
                })
                .catch(error => {
                    testResult.classList.remove('hidden');
                    testResultContent.className = 'p-3 rounded-lg bg-red-100 text-red-800';
                    testResultContent.innerHTML = '<strong>Error:</strong> ' + error.message;
                })
                .finally(() => {
                    testBtn.disabled = false;
                    testLoading.classList.add('hidden');
                    testIcon.classList.remove('hidden');
                    testText.textContent = 'Test Again';
                });
            });

            // Update port when encryption changes
            document.querySelectorAll('input[name="encryption"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    const portSelect = document.getElementById('port');
                    if (this.value === 'ssl') {
                        portSelect.value = '465';
                    } else if (this.value === 'tls') {
                        portSelect.value = '587';
                    } else {
                        portSelect.value = '25';
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
