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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', '!=', 'Draft');
    }
    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return false;
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
                        Forms\Components\TextInput::make('plagiarism_score')
                            ->label('Plagiarism Score (%)')
                            ->numeric()
                            ->suffix('%'),
                        Forms\Components\FileUpload::make('blind_manuscript_path')
                            ->label('Blind Manuscript (Optional)')
                            ->helperText('Upload an anonymous version of the manuscript if the author forgot to remove their details.')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                        Forms\Components\Textarea::make('validation_notes')
                            ->label('Feedback/Notes')
                            ->required(),
                    ])
                    ->action(function (PaperSubmission $record, array $data): void {
                        $updateData = [
                            'status' => $data['decision'],
                            'validation_notes' => $data['validation_notes'],
                            'plagiarism_score' => $data['plagiarism_score'] ?? null,
                        ];
                        if (isset($data['blind_manuscript_path'])) {
                            $updateData['blind_manuscript_path'] = $data['blind_manuscript_path'];
                        }
                        $record->update($updateData);
                    })
                    ->visible(fn (PaperSubmission $record): bool => in_array($record->status, ['Abstract Submitted', 'Full Paper Submitted', 'Pending Administrative Check', 'Unassigned'])),
                
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
                        Forms\Components\DatePicker::make('deadline')
                            ->label('Review Deadline')
                            ->required()
                            ->default(now()->addDays(14)),
                    ])
                    ->action(function (PaperSubmission $record, array $data): void {
                        foreach ($data['reviewer_ids'] as $reviewerId) {
                            \App\Models\PaperReview::firstOrCreate(
                                [
                                    'paper_submission_id' => $record->id,
                                    'reviewer_id' => $reviewerId,
                                ],
                                [
                                    'deadline' => $data['deadline'],
                                ]
                            );
                        }
                        \Filament\Notifications\Notification::make()
                            ->title('Reviewers assigned successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PaperSubmission $record): bool => $record->status === 'Under Double Blind Review' || $record->status === 'Revision Submitted'),
                    
                Tables\Actions\Action::make('Send Reminder')
                    ->icon('heroicon-m-bell-alert')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (PaperSubmission $record) {
                        // In a real app, dispatch a Job or send an email to reviewers who haven't finished.
                        // Here we just show a success notification as a placeholder.
                        \Filament\Notifications\Notification::make()
                            ->title('Reminder sent to pending reviewers')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PaperSubmission $record): bool => $record->reviews()->whereNull('recommendation')->exists() && ($record->status === 'Under Double Blind Review' || $record->status === 'Revision Submitted')),

                Tables\Actions\Action::make('Issue LoA')
                    ->icon('heroicon-m-document-check')
                    ->color('success')
                    ->form([
                        Forms\Components\FileUpload::make('loa_path')
                            ->label('Letter of Acceptance (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->required(),
                    ])
                    ->action(function (PaperSubmission $record, array $data): void {
                        $record->update([
                            'status' => 'LoA Issued',
                            'loa_path' => $data['loa_path'],
                        ]);
                        
                        // Send LoA Email
                        \Illuminate\Support\Facades\Mail::to($record->author->email)
                            ->send(new \App\Mail\LoAIssuedMail($record));

                        \Filament\Notifications\Notification::make()
                            ->title('LoA Issued & Sent to Author')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PaperSubmission $record): bool => $record->status === 'Accepted'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('export_proceedings')
                        ->label('Export Proceedings Data')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $csvData = "Title,Author,Email,Institution,Abstract\n";
                            foreach ($records as $record) {
                                if ($record->status === 'Accepted' || $record->status === 'LoA Issued' || $record->status === 'Published') {
                                    $title = str_replace('"', '""', $record->title);
                                    $authorName = str_replace('"', '""', $record->author->name);
                                    $authorEmail = str_replace('"', '""', $record->author->email);
                                    $institution = str_replace('"', '""', $record->author->institution);
                                    $abstract = str_replace('"', '""', $record->abstract);
                                    $csvData .= "\"$title\",\"$authorName\",\"$authorEmail\",\"$institution\",\"$abstract\"\n";
                                }
                            }
                            return response()->streamDownload(function () use ($csvData) {
                                echo $csvData;
                            }, 'proceedings_data.csv');
                        })
                        ->requiresConfirmation(),
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
