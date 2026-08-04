<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaperSubmissionResource\Pages;
use App\Filament\Resources\PaperSubmissionResource\RelationManagers;
use App\Models\PaperSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaperSubmissionResource extends Resource
{
    protected static ?string $model = PaperSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Event Management';

    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('conference_id')
                    ->relationship('conference', 'name')
                    ->required(),
                Forms\Components\Select::make('conference_track_id')
                    ->relationship('track', 'name'),
                Forms\Components\Select::make('user_id')
                    ->relationship('author', 'name')
                    ->label('Author')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('abstract')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('keywords')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('full_paper_path')
                    ->label('Full Paper Document')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                Forms\Components\FileUpload::make('presentation_file_path')
                    ->label('Presentation File')
                    ->acceptedFileTypes(['application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation']),
                Forms\Components\Select::make('status')
                    ->options([
                        'Abstract Submitted' => 'Abstract Submitted',
                        'Full Paper Submitted' => 'Full Paper Submitted',
                        'Pending Administrative Check' => 'Pending Administrative Check',
                        'Administrative Rejected' => 'Administrative Rejected',
                        'Under Double Blind Review' => 'Under Double Blind Review',
                        'Revision Required' => 'Revision Required',
                        'Revision Submitted' => 'Revision Submitted',
                        'Accepted' => 'Accepted',
                        'Rejected' => 'Rejected',
                        'LoA Issued' => 'LoA Issued',
                        'Registered & Paid' => 'Registered & Paid',
                        'Presented' => 'Presented',
                        'Published' => 'Published',
                    ])
                    ->required(),
                Forms\Components\Select::make('presentation_session_id')
                    ->relationship('session', 'name')
                    ->label('Scheduled Session'),
                Forms\Components\Textarea::make('validation_notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('author.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conference.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('track.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Abstract Submitted', 'Full Paper Submitted', 'Revision Submitted' => 'warning',
                        'Pending Administrative Check', 'Under Double Blind Review' => 'info',
                        'Accepted', 'LoA Issued', 'Registered & Paid', 'Presented', 'Published' => 'success',
                        'Administrative Rejected', 'Rejected', 'Revision Required' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('conference_id')
                    ->relationship('conference', 'name')
                    ->label('Conference'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending Administrative Check' => 'Pending Administrative Check',
                        'Under Double Blind Review' => 'Under Double Blind Review',
                        'Accepted' => 'Accepted',
                        'Rejected' => 'Rejected',
                        'LoA Issued' => 'LoA Issued',
                        'Registered & Paid' => 'Registered & Paid',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Administrative Check')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('decision')
                            ->options([
                                'Under Double Blind Review' => 'Pass (Proceed to Review)',
                                'Administrative Rejected' => 'Fail (Reject)',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('validation_notes')
                            ->label('Feedback/Notes')
                            ->required(),
                    ])
                    ->action(function (PaperSubmission $record, array $data): void {
                        $record->update([
                            'status' => $data['decision'],
                            'validation_notes' => $data['validation_notes'],
                        ]);
                    })
                    ->visible(fn (PaperSubmission $record): bool => in_array($record->status, ['Abstract Submitted', 'Full Paper Submitted', 'Pending Administrative Check'])),
                
                Tables\Actions\Action::make('Assign Reviewers')
                    ->icon('heroicon-m-users')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('reviewer_ids')
                            ->label('Select Reviewers')
                            ->multiple()
                            ->options(function () {
                                return \App\Models\User::role('reviewer')->pluck('name', 'id');
                            })
                            ->required(),
                    ])
                    ->action(function (PaperSubmission $record, array $data): void {
                        foreach ($data['reviewer_ids'] as $reviewerId) {
                            \App\Models\PaperReview::firstOrCreate([
                                'paper_submission_id' => $record->id,
                                'reviewer_id' => $reviewerId,
                            ]);
                        }
                    })
                    ->visible(fn (PaperSubmission $record): bool => $record->status === 'Under Double Blind Review'),

                Tables\Actions\Action::make('Issue LoA')
                    ->icon('heroicon-m-document-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (PaperSubmission $record): void {
                        $record->update(['status' => 'LoA Issued']);
                        // Trigger LoA email here...
                    })
                    ->visible(fn (PaperSubmission $record): bool => $record->status === 'Accepted'),
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
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaperSubmissions::route('/'),
            'create' => Pages\CreatePaperSubmission::route('/create'),
            'edit' => Pages\EditPaperSubmission::route('/{record}/edit'),
        ];
    }
}
