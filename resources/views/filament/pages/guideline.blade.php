<x-filament-panels::page>
    <x-filament::card>
        <div class="prose max-w-none">
            @forelse($this->getGuidelines() as $guideline)
                <h2>{{ $guideline->title }}</h2>
                <div class="mt-4">
                    {!! $guideline->content !!}
                </div>
                @if(!$loop->last)
                    <hr class="my-6">
                @endif
            @empty
                <p>No guidelines available at the moment.</p>
            @endforelse
        </div>
    </x-filament::card>
</x-filament-panels::page>
