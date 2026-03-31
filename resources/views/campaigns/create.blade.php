<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Campaign') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Create New Campaign</h3>
                    <form method="POST" action="{{ route('campaigns.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Campaign Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" name="subject" id="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="smtp_account_id" class="block text-sm font-medium text-gray-700">SMTP Account</label>
                            <select name="smtp_account_id" id="smtp_account_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">Select SMTP Account</option>
                                @foreach($smtpAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->from_address }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- A/B Testing Toggle -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" name="enable_ab_test" id="enable_ab_test" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="enable_ab_test" class="ml-2 text-sm font-medium text-gray-700">Enable A/B Testing</label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Test different subject lines and content variations</p>
                        </div>

                        <!-- A/B Testing Options (Hidden by default) -->
                        <div id="ab-testing-options" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">A/B Test Variations</h4>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject Line Variations</label>
                                    <textarea name="ab_subjects" id="ab_subjects" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Subject A&#10;Subject B&#10;Subject C"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Enter each subject line on a new line</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Content Variations</label>
                                    <div id="content-variations">
                                        <textarea name="ab_contents[]" rows="5" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-2" placeholder="Content variation 1"></textarea>
                                    </div>
                                    <button type="button" id="add-variation" class="text-sm text-indigo-600 hover:text-indigo-700">+ Add another content variation</button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Distribution</label>
                                        <select name="ab_distribution" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="50-50">50/50 split</option>
                                            <option value="33-33-34">33/33/34 split</option>
                                            <option value="25-25-25-25">25/25/25/25 split</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Winner Criteria</label>
                                        <select name="ab_winner_criteria" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="open_rate">Highest open rate</option>
                                            <option value="click_rate">Highest click rate</option>
                                            <option value="conversion">Highest conversion</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Test Duration</label>
                                    <select name="ab_duration_hours" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="24">24 hours</option>
                                        <option value="48">48 hours</option>
                                        <option value="72">72 hours</option>
                                        <option value="168">1 week</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="html_content" class="block text-sm font-medium text-gray-700">HTML Content</label>
                            <textarea name="html_content" id="html_content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="plain_text_content" class="block text-sm font-medium text-gray-700">Plain Text Content (Optional)</label>
                            <textarea name="plain_text_content" id="plain_text_content" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Campaign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('enable_ab_test').addEventListener('change', function() {
            const abOptions = document.getElementById('ab-testing-options');
            if (this.checked) {
                abOptions.classList.remove('hidden');
            } else {
                abOptions.classList.add('hidden');
            }
        });

        document.getElementById('add-variation').addEventListener('click', function() {
            const container = document.getElementById('content-variations');
            const newTextarea = document.createElement('textarea');
            newTextarea.name = 'ab_contents[]';
            newTextarea.rows = 5;
            newTextarea.className = 'block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-2';
            newTextarea.placeholder = 'Content variation ' + (container.children.length + 1);
            container.appendChild(newTextarea);
        });
    </script>
</x-app-layout>
