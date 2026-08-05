<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-megaphone class="w-6 h-6 text-primary-500" />
                <span>Important Announcements</span>
            </div>
        </x-slot>

        @php
            $announcements = $this->getAnnouncements();
        @endphp

        @if($announcements->isEmpty())
            <div class="text-gray-500 italic py-4 text-center">
                No active announcements at this moment.
            </div>
        @else
            <div class="space-y-4">
                @foreach($announcements as $announcement)
                    @php
                        $bgColor = 'bg-gray-50 dark:bg-gray-800/50';
                        $borderColor = 'border-gray-200 dark:border-gray-700';
                        $iconColor = 'text-primary-500';
                        $icon = 'heroicon-o-information-circle';

                        if ($announcement->urgency === 'important') {
                            $bgColor = 'bg-amber-50 dark:bg-amber-900/20';
                            $borderColor = 'border-amber-200 dark:border-amber-700';
                            $iconColor = 'text-amber-500';
                            $icon = 'heroicon-o-exclamation-triangle';
                        } elseif ($announcement->urgency === 'urgent') {
                            $bgColor = 'bg-danger-50 dark:bg-danger-900/20';
                            $borderColor = 'border-danger-200 dark:border-danger-700';
                            $iconColor = 'text-danger-500';
                            $icon = 'heroicon-o-exclamation-circle';
                        }
                    @endphp
                    
                    <div class="p-4 rounded-xl border {{ $bgColor }} {{ $borderColor }} flex items-start gap-4 transition-all hover:shadow-sm">
                        <div class="shrink-0 mt-1">
                            <x-dynamic-component :component="$icon" class="w-6 h-6 {{ $iconColor }}" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                @if($announcement->link)
                                    <a href="{{ $announcement->link }}" target="_blank" class="hover:underline hover:text-primary-600 transition-colors">
                                        {{ $announcement->text }}
                                        <x-heroicon-o-arrow-top-right-on-square class="inline w-3 h-3 ml-1 text-gray-400" />
                                    </a>
                                @else
                                    {{ $announcement->text }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Posted on {{ $announcement->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
