<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class CustomLogin extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                
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

                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }
    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        $response = parent::authenticate();

        $user = \Filament\Facades\Filament::auth()->user();
        
        if ($user && !$user->is_approved) {
            \Filament\Facades\Filament::auth()->logout();
            throw \Illuminate\Validation\ValidationException::withMessages([
                'data.email' => 'Your account is still pending approval.',
            ]);
        }

        return $response;
    }
}
