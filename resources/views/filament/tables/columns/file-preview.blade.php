@php
    $extension = strtolower(pathinfo($getState(), PATHINFO_EXTENSION));
    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
    $url = \Illuminate\Support\Facades\Storage::url($getState());
@endphp

<div class="flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-lg dark:bg-gray-800">
    @if ($isImage)
        <img src="{{ $url }}" alt="preview" class="object-cover w-full h-full" />
    @else
        @if ($extension === 'pdf')
            <x-heroicon-o-document-text class="w-6 h-6 text-red-500" />
        @elseif (in_array($extension, ['doc', 'docx']))
            <x-heroicon-o-document-text class="w-6 h-6 text-blue-500" />
        @elseif (in_array($extension, ['xls', 'xlsx']))
            <x-heroicon-o-table-cells class="w-6 h-6 text-green-500" />
        @elseif (in_array($extension, ['ppt', 'pptx']))
            <x-heroicon-o-presentation-chart-bar class="w-6 h-6 text-orange-500" />
        @else
            <x-heroicon-o-document class="w-6 h-6 text-gray-500" />
        @endif
    @endif
</div>
