<x-filament-panels::page>
    <x-filament::card>
        <div class="prose max-w-none">
            <h2>Download Templates</h2>
            <p>Please use the official templates below to prepare your manuscript.</p>
            
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                <x-filament::button tag="a" href="#" icon="heroicon-o-document-arrow-down" color="primary">
                    Download English Template (DOCX)
                </x-filament::button>
                
                <x-filament::button tag="a" href="#" icon="heroicon-o-document-arrow-down" color="success">
                    Download Arabic Template (DOCX)
                </x-filament::button>
            </div>
            
            <p style="margin-top: 2rem; font-size: 0.9rem; color: gray;">
                <em>Note: Actual template files can be linked by updating the href attributes in the view file.</em>
            </p>
        </div>
    </x-filament::card>
</x-filament-panels::page>
