<x-filament-panels::page>
    <div x-data="{ 
        scanning: @entangle('isScanning'), 
        progress: 0,
        startSimulation() {
            this.progress = 0;
            let interval = setInterval(() => {
                this.progress += Math.floor(Math.random() * 15) + 5;
                if (this.progress >= 100) {
                    this.progress = 100;
                    clearInterval(interval);
                    setTimeout(() => { $wire.completeScan(); }, 500);
                }
            }, 500);
        }
    }" 
    x-init="$watch('scanning', value => { if(value) startSimulation() })">

        @if (! $scanComplete && ! $isScanning)
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                <h2 class="text-xl font-bold mb-4">Plagiarism Checker Simulator</h2>
                <p class="text-gray-500 mb-6">Upload your document or paste the text content below to check for plagiarism against our comprehensive database of academic publications and web sources.</p>
                
                <form wire:submit="startScan">
                    {{ $this->form }}

                    <div class="mt-6 flex justify-end">
                        <x-filament::button type="submit" size="lg" icon="heroicon-o-play">
                            Start Scan
                        </x-filament::button>
                    </div>
                </form>
            </div>
        @endif

        @if ($isScanning)
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-12 text-center max-w-3xl mx-auto flex flex-col items-center justify-center">
                <div class="relative w-32 h-32 mb-6">
                    <svg class="w-full h-full text-gray-200 dark:text-gray-700" viewBox="0 0 36 36">
                        <path class="stroke-current" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <svg class="absolute top-0 left-0 w-full h-full text-primary-600 transition-all duration-300" viewBox="0 0 36 36" :stroke-dasharray="progress + ', 100'">
                        <path class="stroke-current" stroke-width="3" fill="none" stroke-linecap="round" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-2xl font-bold text-primary-600" x-text="progress + '%'"></span>
                    </div>
                </div>
                
                <h2 class="text-2xl font-bold mb-2 animate-pulse">Scanning Document...</h2>
                <p class="text-gray-500">Checking against billions of web pages and academic journals.</p>
                
                <div class="mt-8 text-sm text-gray-400 max-w-md h-6">
                    <div x-show="progress < 30">Uploading document to server...</div>
                    <div x-show="progress >= 30 && progress < 60" style="display: none;">Extracting text and formatting...</div>
                    <div x-show="progress >= 60 && progress < 90" style="display: none;">Analyzing similarities with database...</div>
                    <div x-show="progress >= 90" style="display: none;">Generating comprehensive report...</div>
                </div>
            </div>
        @endif

        @if ($scanComplete)
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-8 text-center max-w-3xl mx-auto mt-4">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-2xl font-bold">Scan Results</h2>
                    <span class="text-sm text-gray-500">Checked on {{ now()->format('M d, Y H:i') }}</span>
                </div>
                
                <div class="relative w-48 h-48 mx-auto mb-8 flex flex-col items-center justify-center rounded-full border-8 shadow-inner
                    {{ $similarityScore <= 15 ? 'border-success-500 text-success-600 dark:text-success-500 bg-success-50 dark:bg-success-900/20' : 
                      ($similarityScore <= 25 ? 'border-warning-500 text-warning-600 dark:text-warning-500 bg-warning-50 dark:bg-warning-900/20' : 
                      'border-danger-500 text-danger-600 dark:text-danger-500 bg-danger-50 dark:bg-danger-900/20') }}">
                    <div class="text-6xl font-black">{{ $similarityScore }}%</div>
                    <div class="text-sm font-medium mt-1 opacity-80">Similarity</div>
                </div>
                
                <h3 class="text-2xl font-semibold mb-3">
                    @if ($similarityScore <= 15)
                        <span class="text-success-600 dark:text-success-500 flex items-center justify-center gap-2">
                            <x-heroicon-o-check-circle class="w-8 h-8" />
                            Excellent! Document is original.
                        </span>
                    @elseif ($similarityScore <= 25)
                        <span class="text-warning-600 dark:text-warning-500 flex items-center justify-center gap-2">
                            <x-heroicon-o-exclamation-triangle class="w-8 h-8" />
                            Caution: Some similarities found.
                        </span>
                    @else
                        <span class="text-danger-600 dark:text-danger-500 flex items-center justify-center gap-2">
                            <x-heroicon-o-x-circle class="w-8 h-8" />
                            Warning: High plagiarism detected!
                        </span>
                    @endif
                </h3>
                
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-xl mx-auto leading-relaxed">
                    This is a simulated result. The document has been checked against our comprehensive database of internet sources and academic publications. 
                    @if($similarityScore > 15) Please review the highlighted sections in the full report. @endif
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 text-left">
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="text-sm text-gray-500 mb-1">Words Scanned</div>
                        <div class="font-bold text-xl">{{ rand(2500, 8500) }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="text-sm text-gray-500 mb-1">Sources Found</div>
                        <div class="font-bold text-xl">{{ rand(2, 15) }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="text-sm text-gray-500 mb-1">Processing Time</div>
                        <div class="font-bold text-xl">{{ rand(12, 45)/10 }}s</div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <x-filament::button wire:click="$set('scanComplete', false)" color="gray" size="lg" icon="heroicon-o-arrow-path">
                        Scan Another
                    </x-filament::button>
                    <x-filament::button wire:click="downloadReport" color="primary" size="lg" icon="heroicon-o-arrow-down-tray">
                        Download Report
                    </x-filament::button>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
