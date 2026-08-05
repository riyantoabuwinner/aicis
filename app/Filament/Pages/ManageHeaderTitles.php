<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use App\Models\Setting;

class ManageHeaderTitles extends Page implements HasForms
{
    protected static ?int $navigationSort = 3;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Site Setting';
    protected static ?string $navigationLabel = 'Judul Header';
    protected static ?string $title = 'Edit Judul & Sub Judul';
    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }
    
    protected static string $view = 'filament.pages.manage-header-titles';

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
                Section::make('Header Text')
                    ->description('Manage your site title and subtitle on the header.')
                    ->schema([
                        TextInput::make('site_title')
                            ->label('Judul Utama (Title)')
                            ->placeholder('e.g., AICIS 2026')
                            ->required(),
                            
                        TextInput::make('site_subtitle')
                            ->label('Sub Judul (Subtitle)')
                            ->placeholder('e.g., UIN SIBER SYEKH NURJATI')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update($this->form->getState());

        Notification::make()
            ->title('Header titles updated successfully.')
            ->success()
            ->send();
    }
}
