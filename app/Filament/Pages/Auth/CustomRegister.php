<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;

class CustomRegister extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\FileUpload::make('avatar_url')
                    ->label('Profile Photo')
                    ->avatar()
                    ->directory('avatars'),
                TextInput::make('front_title')
                    ->label('Front Title (e.g. Prof., Dr.)')
                    ->maxLength(255),
                $this->getNameFormComponent()
                    ->label('Full Name'),
                TextInput::make('back_title')
                    ->label('Back Title (e.g. Ph.D, M.Sc)')
                    ->maxLength(255),
                $this->getEmailFormComponent()
                    ->label('Email Address'),
                TextInput::make('whatsapp_number')
                    ->label('WhatsApp Number')
                    ->placeholder('e.g., +6281234567890')
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
                
                $this->getPasswordFormComponent()
                    ->rule(
                        Password::min(8)
                            ->letters()
                            ->mixedCase()
                            ->numbers()
                            ->symbols()
                    )
                    ->helperText('Password must be at least 8 characters long, and contain at least one uppercase letter, one lowercase letter, one number, and one symbol.'),
                    
                $this->getPasswordConfirmationFormComponent(),
                
                Placeholder::make('captcha_image')
                    ->label('Security Check')
                    ->content(new HtmlString('<img src="' . captcha_src('flat') . '" alt="captcha" class="mb-2 rounded" onclick="this.src=\''.captcha_src('flat').'?\' + Math.random()" style="cursor:pointer; margin: 0 auto; display: block;" title="Click to refresh">')),
                    
                TextInput::make('captcha')
                    ->label('Enter Captcha')
                    ->placeholder('Type the code above')
                    ->required()
                    ->rules(['required', 'captcha'])
                    ->validationMessages([
                        'captcha' => 'Invalid CAPTCHA code.',
                    ]),
            ])
            ->statePath('data');
    }
    public function register(): ?\Filament\Http\Responses\Auth\Contracts\RegistrationResponse
    {
        $this->rateLimit(2);

        $user = $this->wrapInDatabaseTransaction(fn () => $this->handleRegistration($this->form->getState()));

        // Send the registered email
        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserRegisteredMail($user));

        // Send notification to admins
        $admins = \App\Models\User::role(['superadmin', 'admin'])->get();
        foreach ($admins as $admin) {
            \Filament\Notifications\Notification::make()
                ->title('New User Registration')
                ->body("{$user->name} telah mendaftar dan menunggu persetujuan.")
                ->icon('heroicon-o-user-plus')
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->label('View Applicant')
                        ->url(\App\Filament\Resources\PendingUserResource::getUrl('index'))
                        ->markAsRead(),
                ])
                ->sendToDatabase($admin);
        }

        // Redirect to our custom success page
        $this->redirect('/registration-success');
        
        return null;
    }
}
