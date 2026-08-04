<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Models\Setting;

class ManageContactSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Site Setting';
    protected static ?string $navigationLabel = 'Contact';
    protected static ?string $title = 'Contact Settings';
    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }
    
    protected static string $view = 'filament.pages.manage-contact-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $this->form->fill($setting->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contact Information')
                    ->description('Manage your public contact details and Google Maps location.')
                    ->schema([
                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->maxLength(65535),
                            
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255),
                            
                        TextInput::make('phone')
                            ->label('Phone / WhatsApp')
                            ->maxLength(255),
                            
                        Textarea::make('google_maps_url')
                            ->label('Google Maps Embed Code')
                            ->helperText('Go to Google Maps -> Share -> Embed a map, and paste the entire <iframe ...> HTML code here.')
                            ->rows(4)
                            ->maxLength(65535),
                    ]),
                Section::make('Social Media')
                    ->description('Links to your social media profiles. Leave blank to hide the icon on the front end.')
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/...'),
                        TextInput::make('twitter_url')
                            ->label('Twitter / X URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/...'),
                        TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/...'),
                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/...'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update($this->form->getState());

        Notification::make()
            ->title('Contact settings updated successfully.')
            ->success()
            ->send();
    }
}
