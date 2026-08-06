<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Actions\Action;

class UserProfile extends Page implements HasInfolists
{
    protected static ?int $navigationSort = 1;
    use InteractsWithInfolists;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Profil';
    protected static ?string $navigationGroup = 'Profile';
    protected static ?string $title = 'Profil Preview';
    protected static string $view = 'filament.pages.user-profile-view';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record(auth()->user())
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        \Filament\Infolists\Components\Group::make([
                            ImageEntry::make('avatar_url')
                                ->hiddenLabel()
                                ->circular()
                                ->extraImgAttributes(['class' => 'mx-auto w-32 h-32 object-cover'])
                                ->extraAttributes(['class' => 'flex justify-center w-full']),
                            TextEntry::make('full_name')
                                ->hiddenLabel()
                                ->size(\Filament\Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->weight(\Filament\Support\Enums\FontWeight::Bold)
                                ->extraAttributes(['class' => 'flex justify-center w-full text-center mt-2'])
                                ->getStateUsing(fn ($record) => $record->getFilamentName()),
                        ])->columnSpanFull(),
                        
                        Grid::make(2)->schema([
                            TextEntry::make('front_title')->label('Front Title'),
                            TextEntry::make('name')->label('Full Name'),
                            TextEntry::make('back_title')->label('Back Title'),
                            TextEntry::make('gender')->label('Gender'),
                        ]),
                    ]),

                Section::make('Kontak & Wilayah')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('email')->label('Email Address'),
                            TextEntry::make('whatsapp_number')->label('WhatsApp Number'),
                            TextEntry::make('nationality')->label('Nationality'),
                            TextEntry::make('province')->label('Province'),
                            TextEntry::make('city')->label('City/Regency'),
                            TextEntry::make('postal_code')->label('Postal Code'),
                            TextEntry::make('address')->label('Address')->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Akademik & Afiliasi')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('highest_education')->label('Highest Education'),
                            TextEntry::make('institution')->label('Institution (Partner)'),
                            TextEntry::make('university')->label('University / College'),
                            TextEntry::make('study_program')->label('Study Program'),
                        ]),
                    ]),

                Section::make('ID Publikasi')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('scopus_id')->label('Scopus ID'),
                            TextEntry::make('google_scholar_id')->label('Google Scholar ID'),
                            TextEntry::make('sinta_id')->label('SINTA ID'),
                            TextEntry::make('orcid_id')->label('ORCID ID'),
                        ]),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Update Profil')
                ->url(EditProfile::getUrl())
                ->icon('heroicon-m-pencil-square')
                ->color('primary'),
        ];
    }
}
