<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class GalleryResource extends Resource
{
    protected static ?int $navigationSort = 4;
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Gallery';
    protected static ?string $modelLabel = 'Gallery';

    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file_path')
                    ->label('File/Image')
                    ->directory('gallery')
                    ->required(),
                Forms\Components\TextInput::make('caption')
                    ->label('Caption'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ViewColumn::make('file_path')
                    ->view('filament.tables.columns.file-preview')
                    ->label('Preview'),
                Tables\Columns\TextColumn::make('file_url')
                    ->label('URL')
                    ->state(fn ($record) => Storage::url($record->file_path))
                    ->copyable()
                    ->copyMessage('URL copied successfully!')
                    ->limit(30)
                    ->tooltip(fn ($record) => Storage::url($record->file_path)),
                Tables\Columns\TextColumn::make('caption')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->caption),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-s-eye')
                    ->color('gray')
                    ->iconButton()
                    ->modalHeading('File Preview')
                    ->modalContent(fn ($record) => view('filament.pages.actions.file-preview-modal', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
