<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Filament\Resources\MessageResource\RelationManagers;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Messages';
    
    public static function canCreate(): bool
    {
        return false;
    }

    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->disabled(),
                Forms\Components\TextInput::make('email')
                    ->disabled(),
                Forms\Components\Textarea::make('message')
                    ->disabled()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_read')
                    ->label('Mark as Read')
                    ->inline(false),
                Forms\Components\Section::make('Reply History')
                    ->schema([
                        Forms\Components\Repeater::make('replies')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\TextInput::make('sent_at')
                                    ->label('Sent At')
                                    ->disabled(),
                                Forms\Components\TextInput::make('subject')
                                    ->label('Subject')
                                    ->disabled(),
                                Forms\Components\Textarea::make('message')
                                    ->label('Message Content')
                                    ->disabled(),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columns(2),
                    ])
                    ->visible(fn ($record) => $record && !empty($record->replies)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('message')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->message)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_read')
                    ->label('Read'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->placeholder('All Messages')
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
            ])
            ->actions([
                Tables\Actions\Action::make('reply_email')
                    ->hiddenLabel()
                    ->tooltip('Reply by Email')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->default(fn (Message $record) => 'Re: Message from ' . config('app.name')),
                        Forms\Components\Textarea::make('reply_message')
                            ->label('Message')
                            ->required()
                            ->rows(5),
                    ])
                    ->action(function (array $data, Message $record) {
                        $setting = \App\Models\Setting::first();
                        
                        if (! $setting || ! $setting->smtp_host) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('SMTP Settings are incomplete. Please configure them in Site Setting -> Notification Setup.')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            config([
                                'mail.default' => 'smtp',
                                'mail.mailers.smtp.host' => $setting->smtp_host,
                                'mail.mailers.smtp.port' => $setting->smtp_port,
                                'mail.mailers.smtp.username' => $setting->smtp_username,
                                'mail.mailers.smtp.password' => $setting->smtp_password,
                                'mail.mailers.smtp.encryption' => $setting->smtp_encryption,
                                'mail.mailers.smtp.timeout' => 5,
                                'mail.from.address' => $setting->mail_from_address ?? 'noreply@example.com',
                                'mail.from.name' => $setting->mail_from_name ?? 'Application',
                            ]);

                            app('mail.manager')->purge('smtp');

                            \Illuminate\Support\Facades\Mail::raw($data['reply_message'], function ($message) use ($record, $data) {
                                $message->to($record->email)
                                        ->subject($data['subject']);
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Success')
                                ->body('Reply sent to ' . $record->email)
                                ->success()
                                ->send();

                            $replies = $record->replies ?? [];
                            $replies[] = [
                                'subject' => $data['subject'],
                                'message' => $data['reply_message'],
                                'sent_at' => now()->format('Y-m-d H:i:s'),
                            ];
                            $record->update(['replies' => $replies, 'is_read' => true]);

                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to send email')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel()
                    ->tooltip('View Details'),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->tooltip('Update Status'),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->tooltip('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMessages::route('/'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}
