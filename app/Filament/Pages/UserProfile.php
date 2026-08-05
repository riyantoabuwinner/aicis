<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class UserProfile extends Page implements HasForms
{
    protected static ?int $navigationSort = 1;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Edit Profile';
    protected static ?string $navigationGroup = 'Profile';
    protected static ?string $title = 'Edit Profile';
    protected static string $view = 'filament.pages.user-profile';
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(auth()->user()->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Grid::make(2)
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('avatar_url')
                            ->label('Profile Photo')
                            ->avatar()
                            ->disk('public')
                            ->directory('avatars')
                            ->columnSpanFull(),
                        TextInput::make('front_title')
                            ->label('Front Title (e.g. Prof., Dr.)')
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('back_title')
                            ->label('Back Title (e.g. Ph.D, M.Sc)')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        \Filament\Forms\Components\Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                            ])
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Select::make('nationality')
                            ->label('Nationality')
                            ->options([
                                'Indonesian Citizen' => 'Indonesian Citizen',
                                'Foreign Citizen' => 'Foreign Citizen',
                            ])
                            ->required()
                            ->live(),
                        TextInput::make('province_text')
                            ->label('Province')
                            ->required()
                            ->maxLength(255)
                            ->visible(fn (\Filament\Forms\Get $get) => $get('nationality') === 'Foreign Citizen')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (TextInput $component, $state, $record) {
                                if ($record && $record->nationality === 'Foreign Citizen') {
                                    $component->state($record->province);
                                }
                            })
                            ->afterStateUpdated(fn ($state, callable $set) => $set('province', $state)),
                        \Filament\Forms\Components\Select::make('province_select')
                            ->label('Province')
                            ->options(fn () => \App\Models\Province::pluck('name', 'name'))
                            ->required()
                            ->live()
                            ->searchable()
                            ->visible(fn (\Filament\Forms\Get $get) => $get('nationality') !== 'Foreign Citizen')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (\Filament\Forms\Components\Select $component, $state, $record) {
                                if ($record && $record->nationality !== 'Foreign Citizen') {
                                    $component->state($record->province);
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('province', $state);
                                $set('city_select', null);
                                $set('postal_code_select', null);
                            }),
                        \Filament\Forms\Components\Hidden::make('province'),

                        TextInput::make('city_text')
                            ->label('City/Regency')
                            ->required()
                            ->maxLength(255)
                            ->visible(fn (\Filament\Forms\Get $get) => $get('nationality') === 'Foreign Citizen')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (TextInput $component, $state, $record) {
                                if ($record && $record->nationality === 'Foreign Citizen') {
                                    $component->state($record->city);
                                }
                            })
                            ->afterStateUpdated(fn ($state, callable $set) => $set('city', $state)),
                        \Filament\Forms\Components\Select::make('city_select')
                            ->label('City/Regency')
                            ->options(function (\Filament\Forms\Get $get) {
                                $province = \App\Models\Province::where('name', $get('province_select'))->first();
                                if (!$province) return [];
                                return $province->cities->pluck('name', 'name');
                            })
                            ->required()
                            ->live()
                            ->searchable()
                            ->visible(fn (\Filament\Forms\Get $get) => $get('nationality') !== 'Foreign Citizen')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (\Filament\Forms\Components\Select $component, $state, $record) {
                                if ($record && $record->nationality !== 'Foreign Citizen') {
                                    $component->state($record->city);
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('city', $state);
                                $set('postal_code_select', null);
                            }),
                        \Filament\Forms\Components\Hidden::make('city'),

                        TextInput::make('postal_code_text')
                            ->label('Postal Code')
                            ->required()
                            ->maxLength(50)
                            ->visible(fn (\Filament\Forms\Get $get) => $get('nationality') === 'Foreign Citizen')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (TextInput $component, $state, $record) {
                                if ($record && $record->nationality === 'Foreign Citizen') {
                                    $component->state($record->postal_code);
                                }
                            })
                            ->afterStateUpdated(fn ($state, callable $set) => $set('postal_code', $state)),
                        \Filament\Forms\Components\Select::make('postal_code_select')
                            ->label('Postal Code')
                            ->options(function (\Filament\Forms\Get $get) {
                                $cityName = $get('city_select');
                                if (empty($cityName)) return [];
                                
                                // Call external API for real-time postal codes because 90k database rows is too heavy
                                try {
                                    // Remove prefixes like 'Kota ' or 'Kab. ' to improve search accuracy
                                    $cleanCity = str_ireplace(['Kota ', 'Kab. ', 'Kabupaten '], '', $cityName);
                                    
                                    $response = \Illuminate\Support\Facades\Http::timeout(10)->get('https://kodepos.vercel.app/search?q=' . urlencode(trim($cleanCity)));
                                    if ($response->successful() && $response->json('code') === 'OK') {
                                        $data = $response->json('data');
                                        if (is_array($data) && count($data) > 0) {
                                            return collect($data)->pluck('code', 'code')->unique()->sort()->toArray();
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // Fallback if API fails
                                }
                                
                                return [];
                            })
                            ->required()
                            ->searchable()
                            ->visible(fn (\Filament\Forms\Get $get) => $get('nationality') !== 'Foreign Citizen')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (\Filament\Forms\Components\Select $component, $state, $record) {
                                if ($record && $record->nationality !== 'Foreign Citizen') {
                                    $component->state($record->postal_code);
                                }
                            })
                            ->afterStateUpdated(fn ($state, callable $set) => $set('postal_code', $state)),
                        \Filament\Forms\Components\Hidden::make('postal_code'),
                        \Filament\Forms\Components\Select::make('highest_education')
                            ->label('Highest Education')
                            ->options([
                                'S1' => 'Bachelor (S1)',
                                'S2' => 'Master (S2)',
                                'S3' => 'Doctorate (S3)',
                                'Other' => 'Other',
                            ])
                            ->required(),
                        TextInput::make('study_program')
                            ->label('Study Program')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('university')
                            ->label('University / College')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('institution')
                            ->label('Institution/ Affiliation')
                            ->options(\App\Models\OfficialPartner::pluck('name', 'name'))
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('new_institution')
                                    ->label('Institution Name')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return $data['new_institution'];
                            }),
                        TextInput::make('scopus_id')
                            ->label('Scopus ID')
                            ->maxLength(255)
                            ->nullable(),
                        TextInput::make('google_scholar_id')
                            ->label('Google Scholar ID')
                            ->maxLength(255)
                            ->nullable(),
                        TextInput::make('sinta_id')
                            ->label('SINTA ID')
                            ->maxLength(255)
                            ->nullable(),
                        TextInput::make('orcid_id')
                            ->label('ORCID ID')
                            ->maxLength(255)
                            ->nullable(),
                    ]),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255)
                    ->label('New Password (leave blank to keep current)'),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();
        auth()->user()->update($data);
        
        Notification::make()
            ->success()
            ->title('Profile updated successfully')
            ->send();
            
        return redirect()->to('/admin');
    }
}
