<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Users';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_approved', true);
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar_url')
                    ->label('Profile Photo')
                    ->avatar()
                    ->directory('avatars'),
                Forms\Components\TextInput::make('front_title')
                    ->label('Front Title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Full Name')
                    ->required(),
                Forms\Components\TextInput::make('back_title')
                    ->label('Back Title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('whatsapp_number')
                    ->label('WhatsApp Number')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\Select::make('highest_education')
                    ->label('Highest Education')
                    ->options([
                        'S1' => 'Bachelor (S1)',
                        'S2' => 'Master (S2)',
                        'S3' => 'Doctorate (S3)',
                        'Other' => 'Other',
                    ]),
                Forms\Components\TextInput::make('study_program')
                    ->label('Study Program')
                    ->maxLength(255),
                Forms\Components\TextInput::make('university')
                    ->label('University / College')
                    ->maxLength(255),
                Forms\Components\Select::make('institution')
                    ->label('Institution (Partner)')
                    ->options(\App\Models\OfficialPartner::pluck('name', 'name'))
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('new_institution')
                            ->label('Institution Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return $data['new_institution'];
                    }),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('is_approved')
                    ->sortable()
                    ->visible(fn () => auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Reset Password')
                    ->icon('heroicon-m-key')
                    ->color('danger')
                    ->hiddenLabel()
                    ->tooltip('Reset Password')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->password()
                            ->required()
                            ->minLength(8),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'password' => \Illuminate\Support\Facades\Hash::make($data['new_password']),
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Password Reset Successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin')),

                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-s-eye')
                    ->iconButton()
                    ->visible(fn () => auth()->user()->hasRole('superadmin')),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->visible(fn () => auth()->user()->hasRole('superadmin')),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->visible(fn () => auth()->user()->hasRole('superadmin')),
                Tables\Actions\Action::make('impersonate')
                    ->label('Impersonate')
                    ->tooltip('Impersonate')
                    ->iconButton()
                    ->icon('heroicon-o-users')
                    ->color('warning')
                    ->visible(fn ($record) => auth()->user()->hasRole('superadmin') && auth()->id() !== $record->id)
                    ->action(function ($record) {
                        auth()->login($record);
                        session()->save();
                        return redirect('/admin');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('superadmin')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
