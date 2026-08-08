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
