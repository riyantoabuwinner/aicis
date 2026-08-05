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
                            ->label('Institution (Partner)')
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
