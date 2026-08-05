<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    protected static ?int $navigationSort = 33;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                TextInput::make('whatsapp_number')
                    ->label('WhatsApp Number')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                TextInput::make('institution')
                    ->label('Institution')
                    ->required()
                    ->maxLength(255),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
