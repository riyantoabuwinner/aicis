<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendingUserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PendingUserResource extends Resource
{
    protected static ?int $navigationSort = 1;
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Pending Registrations';
    protected static ?string $modelLabel = 'Pending User';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_approved', false);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approved', false)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('whatsapp_number')
                    ->label('WhatsApp Number')
                    ->tel()
                    ->maxLength(20),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-s-clipboard-document-check')
                    ->color('primary')
                    ->iconButton()
                    ->form([
                        Forms\Components\Radio::make('decision')
                            ->label('Decision')
                            ->options([
                                'approve' => 'Setujui (Approve)',
                                'reject' => 'Tolak (Reject)',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\Textarea::make('message')
                            ->label('Rejection Reason')
                            ->required()
                            ->visible(fn (Forms\Get $get) => $get('decision') === 'reject'),
                    ])
                    ->action(function (array $data, $record) {
                        if ($data['decision'] === 'approve') {
                            $record->update(['is_approved' => true]);
                            
                            $defaultMessage = "Your account is active. Please log in, update your profile, and proceed.";
                            
                            \Illuminate\Support\Facades\Mail::to($record->email)->send(new \App\Mail\UserApprovedMail($record, $defaultMessage));
                            \Filament\Notifications\Notification::make()
                                ->title('User Approved')
                                ->success()
                                ->send();
                        } else {
                            \Illuminate\Support\Facades\Mail::to($record->email)->send(new \App\Mail\UserRejectedMail($record, $data['message']));
                            $record->delete();
                            \Filament\Notifications\Notification::make()
                                ->title('User Rejected & Deleted')
                                ->danger()
                                ->send();
                        }
                    }),
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
            'index' => Pages\ListPendingUsers::route('/'),
            'create' => Pages\CreatePendingUser::route('/create'),
            'edit' => Pages\EditPendingUser::route('/{record}/edit'),
        ];
    }
}
