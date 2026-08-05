<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Models\Setting;

class ManageNotificationSettings extends Page implements HasForms
{
    protected static ?int $navigationSort = 6;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'Site Setting';
    protected static ?string $navigationLabel = 'Notification Setup';
    protected static ?string $title = 'SMTP & WhatsApp Settings';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

    protected static string $view = 'filament.pages.manage-notification-settings';

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
                Section::make('SMTP Email Settings')
                    ->description('Set up your SMTP email for sending notifications.')
                    ->schema([
                        TextInput::make('smtp_host')
                            ->label('SMTP Host')
                            ->placeholder('smtp.example.com')
                            ->maxLength(255),
                        TextInput::make('smtp_port')
                            ->label('SMTP Port')
                            ->placeholder('587')
                            ->numeric()
                            ->maxLength(255),
                        TextInput::make('smtp_username')
                            ->label('SMTP Username')
                            ->maxLength(255),
                        TextInput::make('smtp_password')
                            ->label('SMTP Password')
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                        Select::make('smtp_encryption')
                            ->label('SMTP Encryption')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                '' => 'None',
                            ])
                            ->default('tls'),
                        TextInput::make('mail_from_address')
                            ->label('Mail From Address')
                            ->email()
                            ->placeholder('noreply@example.com')
                            ->maxLength(255),
                        TextInput::make('mail_from_name')
                            ->label('Mail From Name')
                            ->placeholder('My Application')
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('WhatsApp Settings')
                    ->description('Set up your WhatsApp number for sending messages.')
                    ->schema([
                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp Number')
                            ->placeholder('+6281234567890')
                            ->maxLength(255),
                        TextInput::make('whatsapp_api_key')
                            ->label('WhatsApp API Key / Token (Optional)')
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update($this->form->getState());

        Notification::make()
            ->title('Notification Settings updated successfully.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('test_email')
                ->label('Test Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->form([
                    TextInput::make('email')
                        ->label('Recipient Email')
                        ->email()
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('message')
                        ->label('Email Message')
                        ->default('This is a test email to verify your SMTP settings. If you received this, your email configuration is working correctly!')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->sendTestEmail($data['email'], $data['message']);
                })
        ];
    }

    public function sendTestEmail(string $recipient, string $messageContent = 'Test Email'): void
    {
        $state = $this->form->getState();
        
        if (empty($state['smtp_host'])) {
            Notification::make()->title('Error')->body('SMTP Host is required. Please fill in the SMTP settings first.')->danger()->send();
            return;
        }

        try {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $state['smtp_host'],
                'mail.mailers.smtp.port' => $state['smtp_port'],
                'mail.mailers.smtp.username' => $state['smtp_username'],
                'mail.mailers.smtp.password' => $state['smtp_password'],
                'mail.mailers.smtp.encryption' => $state['smtp_encryption'],
                'mail.mailers.smtp.timeout' => 5, // 5 seconds timeout to prevent fatal error
                'mail.from.address' => $state['mail_from_address'] ?? 'noreply@example.com',
                'mail.from.name' => $state['mail_from_name'] ?? 'Application',
            ]);

            app('mail.manager')->purge('smtp');

            \Illuminate\Support\Facades\Mail::raw($messageContent, function ($message) use ($recipient) {
                $message->to($recipient)
                        ->subject('Test Email - Application Settings');
            });

            Notification::make()->title('Success')->body('Test email sent successfully!')->success()->send();
        } catch (\Exception $e) {
            Notification::make()->title('Failed to send test email')->body($e->getMessage())->danger()->send();
        }
    }
}
