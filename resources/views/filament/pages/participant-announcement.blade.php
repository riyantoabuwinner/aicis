<x-filament-panels::page>
    <x-filament::card>
        <div class="prose max-w-none mb-6">
            <p class="text-gray-600 dark:text-gray-400">
                Welcome to the announcements page. Below you will find important updates and information regarding the conference.
            </p>
        </div>

        @php
            $announcements = $this->getAnnouncements();
        @endphp

        @if($announcements->isEmpty())
            <div class="text-gray-500 italic py-4 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
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
                    
                    <div class="p-5 rounded-xl border {{ $bgColor }} {{ $borderColor }} flex items-start gap-4 transition-all hover:shadow-md">
                        <div class="shrink-0 mt-1">
                            <x-dynamic-component :component="$icon" class="w-7 h-7 {{ $iconColor }}" />
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                @if($announcement->link)
                                    <a href="{{ $announcement->link }}" target="_blank" class="hover:underline hover:text-primary-600 transition-colors">
                                        {{ $announcement->text }}
                                        <x-heroicon-o-arrow-top-right-on-square class="inline w-3 h-3 ml-1 text-gray-400" />
                                    </a>
                                @else
                                    {{ $announcement->text }}
                                @endif
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Posted on {{ $announcement->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::card>
</x-filament-panels::page>
