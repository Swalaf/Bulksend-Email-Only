<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data & Privacy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Your Data Rights</h3>
                        <p class="text-sm text-gray-600">Manage your personal data and privacy settings</p>
                    </div>

                    <!-- Data Export Section -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Export Your Data</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Download a complete copy of all your data stored in BulkSend, including campaigns, subscribers, analytics, and account information.
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                @if($data['last_export'])
                                    Last exported: {{ \Carbon\Carbon::parse($data['last_export'])->format('M j, Y g:i A') }}
                                @else
                                    Never exported
                                @endif
                            </div>
                            <form method="POST" action="{{ route('gdpr.export') }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Export Data
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Data Categories -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Data We Store</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($data['data_categories'] as $category)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $category }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Retention Periods -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Data Retention</h4>
                        <div class="space-y-3">
                            @foreach($data['retention_periods'] as $type => $period)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700">{{ $type }}</span>
                                    <span class="text-sm text-gray-600">{{ $period }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- User Rights -->
                    <div class="mb-8">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Your Rights</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($data['user_rights'] as $right)
                                <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $right }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Account Deletion Section -->
                    <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-lg">
                        <h4 class="text-md font-medium text-red-900 mb-3">Delete Your Account</h4>
                        <p class="text-sm text-red-700 mb-4">
                            Permanently delete your account and all associated data. This action cannot be undone.
                        </p>

                        @if($data['deletion_requested'])
                            <div class="mb-4 p-3 bg-yellow-100 border border-yellow-300 rounded">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">Deletion Requested</p>
                                        <p class="text-sm text-yellow-700">Your account will be deleted on {{ \Carbon\Carbon::parse($data['deletion_date'])->format('M j, Y') }}. You can cancel this request below.</p>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('gdpr.cancel-delete') }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    Cancel Deletion
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('gdpr.delete') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for deletion (optional)</label>
                                    <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Please let us know why you're leaving..."></textarea>
                                </div>

                                <div class="flex items-center">
                                    <input id="confirm_deletion" name="confirm_deletion" type="checkbox" class="rounded border-red-300 text-red-600 shadow-sm focus:ring-red-500">
                                    <label for="confirm_deletion" class="ml-2 text-sm text-gray-900">
                                        I understand that this action will permanently delete my account and all associated data
                                    </label>
                                </div>

                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50" id="delete-btn" disabled>
                                    Delete Account
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Contact Information -->
                    <div class="text-center text-sm text-gray-600">
                        <p>For any questions about your data or privacy rights, please contact our Data Protection Officer at <a href="mailto:privacy@bulksend.com" class="text-blue-600 hover:text-blue-800">privacy@bulksend.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enable/disable delete button based on checkbox
        document.getElementById('confirm_deletion').addEventListener('change', function() {
            document.getElementById('delete-btn').disabled = !this.checked;
        });
    </script>
</x-app-layout>