<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use App\Models\Setting;

class ManageAboutSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationGroup = 'Site Setting';
    protected static ?string $navigationLabel = 'About Info';
    protected static ?string $title = 'Edit About Information';
    
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
                Section::make('About Section Settings')
                    ->description('Manage the text that appears in the About section on the homepage.')
                    ->schema([
                        TextInput::make('about_title')
                            ->label('Title')
                            ->placeholder('e.g., About AICIS')
                            ->maxLength(255),
                        \Filament\Forms\Components\Textarea::make('about_content')
                            ->label('Content')
                            ->maxLength(500)
                            ->rows(4)
                            ->hint('Max 500 characters')
                            ->columnSpanFull(),
                        TextInput::make('about_button_url')
                            ->label('Read More URL')
                            ->placeholder('e.g., /about or https://...'),
                    ])
                    ->columns(1)
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
