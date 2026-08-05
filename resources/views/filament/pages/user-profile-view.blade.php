<x-filament-panels::page>
    {{ $this->infolist }}
    
    <div class="mt-6">
        <x-filament::button tag="a" href="{{ \App\Filament\Pages\EditProfile::getUrl() }}">
            Update Profil
        </x-filament::button>
    </div>
</x-filament-panels::page>
