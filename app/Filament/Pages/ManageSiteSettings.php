<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use App\Models\Setting;

class ManageSiteSettings extends Page implements HasForms
{
    protected static ?int $navigationSort = 4;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Site Setting';
    protected static ?string $navigationLabel = 'Logo & Favicon';
    protected static ?string $title = 'Edit Logo & Favicon';
    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }
    
    protected static string $view = 'filament.pages.manage-site-settings';

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
                Section::make('Branding')
                    ->description('Manage your site logo and favicon.')
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->label('Site Logo (Light/Default)'),
                            
                        FileUpload::make('dark_logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->label('Site Logo (Dark Media)'),
                            
                        FileUpload::make('favicon')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->label('Site Favicon (.ico, .png)'),
                            
                        \Filament\Forms\Components\TextInput::make('site_title')
                            ->label('Site Title')
                            ->maxLength(255),
                            
                        \Filament\Forms\Components\TextInput::make('site_subtitle')
                            ->label('Site Subtitle 1')
                            ->maxLength(255),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update($this->form->getState());

        Notification::make()
            ->title('Settings updated successfully.')
            ->success()
            ->send();
    }
}
