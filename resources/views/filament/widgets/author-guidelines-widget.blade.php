<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-4">
            <div class="p-3 bg-primary-500 rounded-lg text-white">
                <x-heroicon-o-document-text class="w-8 h-8" />
            </div>
            <div>
                <h2 class="text-xl font-bold">Author Guidelines & Template</h2>
                <p class="text-gray-500 text-sm mt-1">Please read the guidelines and use the provided template before submitting your paper.</p>
            </div>
        </div>
        
        <div class="mt-4 flex gap-4">
            <x-filament::button color="primary" tag="a" href="#" target="_blank" icon="heroicon-o-arrow-down-tray">
                Download Guidelines
            </x-filament::button>
            
            <x-filament::button color="success" tag="a" href="#" target="_blank" icon="heroicon-o-document-arrow-down">
                Download Paper Template
            </x-filament::button>
        </div>
        
        <div class="mt-4 text-sm text-gray-600 bg-warning-50 p-3 rounded-lg border border-warning-200">
            <strong>Note for Double-Blind Review:</strong> Please ensure that your manuscript does not contain any author identifying information (Names, Affiliations, etc.) before uploading.
        </div>
    </x-filament::section>
</x-filament-widgets::widget>