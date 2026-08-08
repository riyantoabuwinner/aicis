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
                $this->getNameFormComponent()
                    ->label('Full Name'),
                
                $this->getEmailFormComponent()
                    ->label('Email Address'),
                
                TextInput::make('whatsapp_number')
                    ->label('WhatsApp Number')
                    ->placeholder('e.g., +6281234567890')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                
                \Filament\Forms\Components\Select::make('role')
                    ->label('Register As')
                    ->options([
                        'author' => 'Author (Submit & Present Paper)',
                        'reviewer' => 'Reviewer (Review Submissions)',
                    ])
                    ->default('author')
                    ->required(),
                
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
                
            ])
            ->statePath('data');
    }

    protected function handleRegistration(array $data): \Illuminate\Database\Eloquent\Model
    {
        $role = $data['role'] ?? 'author';
        unset($data['role']);
        
        $user = parent::handleRegistration($data);
        $user->assignRole($role);
        
        return $user;
    }

    public function register(): ?\Filament\Http\Responses\Auth\Contracts\RegistrationResponse
    {
        $this->rateLimit(2);

        $user = $this->wrapInDatabaseTransaction(fn () => $this->handleRegistration($this->form->getState()));

        // Send the registered email
        // Send the registered email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserRegisteredMail($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Registration Mail Error: ' . $e->getMessage());
        }

        // Send notification to admins
        try {
            // Get admins robustly
            $admins = \App\Models\User::whereHas('roles', function($q) {
                $q->whereIn('name', ['superadmin', 'admin']);
            })->get();
            
            // Fallback: If no admins found with roles, at least notify the first user in the database (usually the owner)
            if ($admins->isEmpty()) {
                $firstUser = \App\Models\User::first();
                if ($firstUser) {
                    $admins->push($firstUser);
                }
            }

            foreach ($admins as $admin) {
                \Filament\Notifications\Notification::make()
                    ->title('New User Registration')
                    ->body('Seorang pengguna baru telah mendaftar dan menunggu persetujuan.')
                    ->icon('heroicon-o-user-plus')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->label('View Applicant')
                            ->url('/admin/pending-users') // hardcode URL to avoid any getUrl() crashes outside context
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($admin);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Admin Notification Error: ' . $e->getMessage());
        }

        // Redirect to our custom success page
        $this->redirect('/registration-success');
        
        return null;
    }
}
