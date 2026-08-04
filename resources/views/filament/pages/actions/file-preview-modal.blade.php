@php
    $extension = strtolower(pathinfo($record->file_path, PATHINFO_EXTENSION));
    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
    $url = url(\Illuminate\Support\Facades\Storage::url($record->file_path));
@endphp

<div class="flex flex-col items-center justify-center p-4 space-y-4">
    @if ($isImage)
        <img src="{{ $url }}" alt="Preview" class="max-w-full max-h-[60vh] rounded-lg shadow-md" />
    @else
        <div class="flex flex-col items-center justify-center p-12 bg-gray-100 rounded-lg dark:bg-gray-800 w-full">
            @if ($extension === 'pdf')
                <x-heroicon-o-document-text class="w-24 h-24 text-red-500" />
            @elseif (in_array($extension, ['doc', 'docx']))
                <x-heroicon-o-document-text class="w-24 h-24 text-blue-500" />
            @elseif (in_array($extension, ['xls', 'xlsx']))
                <x-heroicon-o-table-cells class="w-24 h-24 text-green-500" />
            @elseif (in_array($extension, ['ppt', 'pptx']))
                <x-heroicon-o-presentation-chart-bar class="w-24 h-24 text-orange-500" />
            @else
                <x-heroicon-o-document class="w-24 h-24 text-gray-500" />
            @endif
            <p class="mt-4 text-lg font-medium">{{ basename($record->file_path) }}</p>
        </div>
    @endif

    <div class="w-full pt-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL / Link</label>
        <div x-data="{ copied: false }" class="flex gap-2">
            <input type="text" readonly value="{{ $url }}" class="flex-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            <button type="button" 
                    @click="navigator.clipboard.writeText('{{ $url }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:bg-primary-500 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <span x-show="!copied">Copy</span>
                <span x-show="copied">Copied!</span>
            </button>
        </div>
    </div>
</div>
