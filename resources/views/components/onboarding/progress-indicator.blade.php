<div class="w-full" x-data="{ progress: {{ $progress ?? 0 }} }">
    <div class="flex items-center justify-between mb-2">
        @foreach($steps as $key => $step)
            <div class="flex flex-col items-center relative z-10">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-all duration-500 
                    {{ $step['completed'] ? 'bg-indigo-600 text-white' : ($loop->index <= array_search($currentStep, array_keys($steps)) ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-200 text-gray-500') }}">
                    @if($step['completed'])
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        {{ $loop->index + 1 }}
                    @endif
                </div>
                <span class="text-xs mt-1 font-medium {{ $step['completed'] ? 'text-indigo-600' : 'text-gray-500' }}">{{ $step['title'] }}</span>
            </div>
            
            @if(!$loop->last)
                <div class="flex-1 h-1 mx-2 bg-gray-200 rounded relative overflow-hidden">
                    <div class="absolute inset-0 bg-indigo-600 transition-all duration-700 ease-out" 
                         style="width: {{ $step['completed'] ? '100%' : '0%' }}"></div>
                </div>
            @endif
        @endforeach
    </div>
</div>
