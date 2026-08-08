<x-filament-panels::page>
    <x-filament::card>
        <div class="prose max-w-none">
            <h2>Download Files</h2>
            <p>Please find the available templates and files below.</p>
            
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                @forelse($this->getDownloads() as $download)
                    <x-filament::button tag="a" href="{{ \Illuminate\Support\Facades\Storage::url($download->file_path) }}" target="_blank" icon="heroicon-o-document-arrow-down" color="primary">
                        {{ $download->title }}
                    </x-filament::button>
                @empty
                    <p style="color: gray;">No downloadable files available at the moment.</p>
                @endforelse
            </div>
        </div>
    </x-filament::card>
</x-filament-panels::page>
