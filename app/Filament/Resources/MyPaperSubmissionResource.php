<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MyPaperSubmissionResource\Pages;
use App\Filament\Resources\MyPaperSubmissionResource\RelationManagers;
use App\Models\PaperSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MyPaperSubmissionResource extends Resource
{
    protected static ?string $model = PaperSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $navigationLabel = 'My Submissions';
    protected static ?string $modelLabel = 'Submission';
    protected static ?string $pluralModelLabel = 'My Submissions';
    protected static ?string $slug = 'my-submissions';

    public static function canAccess(): bool
    {
        // Allowed for everyone except those who are explicitly reviewers/admins who should use the other panels
        return !auth()->user()->hasRole(['reviewer', 'admin', 'superadmin']) || auth()->user()->hasRole(['author']) || auth()->user()->roles->isEmpty();
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                Forms\Components\Select::make('conference_id')
                    ->relationship('conference', 'name')
                    ->required()
                    ->default(function () {
                        return \App\Models\Conference::where('is_active', true)->first()?->id;
                    }),
                Forms\Components\Select::make('conference_track_id')
                    ->relationship('track', 'name')
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
                    ->label('Full Paper Document (PDF/DOCX)')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                Forms\Components\FileUpload::make('presentation_file_path')
                    ->label('Presentation File (PPTX/PDF)')
                    ->acceptedFileTypes(['application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('conference.name'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Unassigned', 'Abstract Submitted', 'Full Paper Submitted' => 'warning',
                        'In Review', 'Revision', 'Revision Submitted', 'Pending Administrative Check', 'Under Double Blind Review' => 'info',
                        'Accepted', 'LoA Issued', 'Registered & Paid', 'Presented', 'Published' => 'success',
                        'Rejected', 'Administrative Rejected', 'Revision Required' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (PaperSubmission $record): bool => in_array($record->status, [
                        'Draft',
                        'Abstract Submitted', 
                        'Full Paper Submitted', 
                        'Revision Required',
                        'Pending Administrative Check'
                    ])),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Submit')
                    ->icon('heroicon-m-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (PaperSubmission $record) => $record->update(['status' => 'Abstract Submitted']))
                    ->visible(fn (PaperSubmission $record): bool => $record->status === 'Draft'),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (PaperSubmission $record): bool => $record->status === 'Draft'),
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
            'index' => Pages\ListMyPaperSubmissions::route('/'),
            'create' => Pages\CreateMyPaperSubmission::route('/create'),
            'edit' => Pages\EditMyPaperSubmission::route('/{record}/edit'),
        ];
    }
}
