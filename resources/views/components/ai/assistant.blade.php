<div x-data="aiAssistant()" 
     x-init="init()"
     class="relative">
    <!-- AI Assistant Button -->
    <button 
        @click="togglePanel()"
        class="fixed bottom-6 right-6 w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center z-50 group"
        title="AI Assistant"
    >
        <svg x-show="!isOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <svg x-show="isOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white" x-show="hasNotification"></span>
    </button>

    <!-- AI Panel -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-24 right-6 w-96 bg-white dark:bg-gray-800 rounded-xl shadow-2xl z-50 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-white font-medium">AI Assistant</span>
            </div>
            <button @click="togglePanel()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <button @click="activeTab = 'generate'" 
                    :class="activeTab === 'generate' ? 'border-indigo-500 text-indigo-600' : 'border-transparent'"
                    class="flex-1 px-4 py-2 text-sm font-medium border-b-2 -mb-px">
                Generate
            </button>
            <button @click="activeTab = 'improve'" 
                    :class="activeTab === 'improve' ? 'border-indigo-500 text-indigo-600' : 'border-transparent'"
                    class="flex-1 px-4 py-2 text-sm font-medium border-b-2 -mb-px">
                Improve
            </button>
            <button @click="activeTab = 'subjects'" 
                    :class="activeTab === 'subjects' ? 'border-indigo-500 text-indigo-600' : 'border-transparent'"
                    class="flex-1 px-4 py-2 text-sm font-medium border-b-2 -mb-px">
                Subjects
            </button>
        </div>

        <!-- Content -->
        <div class="p-4 max-h-96 overflow-y-auto">
            <!-- Generate Tab -->
            <div x-show="activeTab === 'generate'" x-cloak>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Topic</label>
                        <input type="text" x-model="generateTopic" 
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm"
                               placeholder="e.g., Summer sale announcement">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tone</label>
                            <select x-model="generateTone" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                                <option value="professional">Professional</option>
                                <option value="friendly">Friendly</option>
                                <option value="casual">Casual</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Length</label>
                            <select x-model="generateLength" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                                <option value="short">Short</option>
                                <option value="medium">Medium</option>
                                <option value="long">Long</option>
                            </select>
                        </div>
                    </div>
                    <button @click="generateContent()" 
                            :disabled="isLoading || !generateTopic"
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="isLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isLoading ? 'Generating...' : 'Generate Content'"></span>
                    </button>
                </div>
            </div>

            <!-- Improve Tab -->
            <div x-show="activeTab === 'improve'" x-cloak>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Content</label>
                        <textarea x-model="improveContent" rows="4"
                                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm"
                                  placeholder="Paste your email content here..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rewrite Tone</label>
                        <select x-model="improveTone" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm">
                            <option value="professional">More Professional</option>
                            <option value="friendly">More Friendly</option>
                            <option value="casual">More Casual</option>
                            <option value="urgent">More Urgent</option>
                        </select>
                    </div>
                    <button @click="improveContent()" 
                            :disabled="isLoading || !improveContent"
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="isLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isLoading ? 'Rewriting...' : 'Rewrite Content'"></span>
                    </button>
                </div>
            </div>

            <!-- Subjects Tab -->
            <div x-show="activeTab === 'subjects'" x-cloak>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Campaign Topic</label>
                        <input type="text" x-model="subjectTopic" 
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm"
                               placeholder="e.g., New product launch">
                    </div>
                    <button @click="generateSubjects()" 
                            :disabled="isLoading || !subjectTopic"
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="isLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isLoading ? 'Generating...' : 'Generate Subject Lines'"></span>
                    </button>
                </div>

                <!-- Generated Subjects -->
                <div x-show="generatedSubjects.length > 0" class="mt-4 space-y-2">
                    <template x-for="(subject, index) in generatedSubjects" :key="index">
                        <div @click="selectSubject(subject)" 
                             class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                            <p class="text-sm text-gray-800 dark:text-gray-200" x-text="subject"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Results -->
            <div x-show="result" class="mt-4">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">Generated Result</span>
                        <button @click="copyResult()" class="text-xs text-gray-500 hover:text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="text-sm text-gray-700 dark:text-gray-300 prose prose-sm max-w-none" x-html="result"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function aiAssistant() {
    return {
        isOpen: false,
        hasNotification: false,
        activeTab: 'generate',
        isLoading: false,
        result: '',
        
        // Generate tab
        generateTopic: '',
        generateTone: 'professional',
        generateLength: 'medium',
        
        // Improve tab
        improveContent: '',
        improveTone: 'professional',
        
        // Subjects tab
        subjectTopic: '',
        generatedSubjects: [],

        init() {
            // Check if AI is available
            this.checkStatus();
        },

        togglePanel() {
            this.isOpen = !this.isOpen;
            if (!this.isOpen) {
                this.result = '';
                this.generatedSubjects = [];
            }
        },

        async checkStatus() {
            try {
                const response = await fetch('/api/ai/status');
                const data = await response.json();
                this.hasNotification = data.enabled;
            } catch (e) {
                console.error('AI status check failed');
            }
        },

        async generateContent() {
            if (!this.generateTopic) return;
            
            this.isLoading = true;
            this.result = '';

            try {
                const response = await fetch('/api/ai/generate-content', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        topic: this.generateTopic,
                        tone: this.generateTone,
                        length: this.generateLength
                    })
                });
                
                const data = await response.json();
                this.result = data.content || data.template || '';
                
                // Emit event to update editor
                if (this.result) {
                    window.dispatchEvent(new CustomEvent('ai-content-generated', { 
                        detail: { content: this.result }
                    }));
                }
            } catch (e) {
                console.error('Generation failed:', e);
                this.result = '<p class="text-red-500">Failed to generate content. Please try again.</p>';
            }

            this.isLoading = false;
        },

        async improveContent() {
            if (!this.improveContent) return;
            
            this.isLoading = true;
            this.result = '';

            try {
                const response = await fetch('/api/ai/rewrite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        content: this.improveContent,
                        tone: this.improveTone
                    })
                });
                
                const data = await response.json();
                this.result = data.content || '';
                
                if (this.result) {
                    window.dispatchEvent(new CustomEvent('ai-content-generated', { 
                        detail: { content: this.result }
                    }));
                }
            } catch (e) {
                console.error('Rewrite failed:', e);
                this.result = '<p class="text-red-500">Failed to rewrite content. Please try again.</p>';
            }

            this.isLoading = false;
        },

        async generateSubjects() {
            if (!this.subjectTopic) return;
            
            this.isLoading = true;
            this.generatedSubjects = [];

            try {
                const response = await fetch('/api/ai/generate-subjects', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        topic: this.subjectTopic,
                        count: 5
                    })
                });
                
                const data = await response.json();
                this.generatedSubjects = data.subjects || [];
            } catch (e) {
                console.error('Subject generation failed:', e);
            }

            this.isLoading = false;
        },

        selectSubject(subject) {
            window.dispatchEvent(new CustomEvent('ai-subject-selected', { 
                detail: { subject: subject }
            }));
        },

        copyResult() {
            navigator.clipboard.writeText(this.stripHtml(this.result));
        },

        stripHtml(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        }
    };
}
</script>
