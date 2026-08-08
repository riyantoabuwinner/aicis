<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoProfileResource\Pages;
use App\Models\VideoProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VideoProfileResource extends Resource
{
    protected static ?int $navigationSort = 16;
    protected static ?string $model = VideoProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Video Profiles';

    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('youtube_url')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->helperText('Enter the full YouTube URL (e.g., https://www.youtube.com/watch?v=...)'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('youtube_url')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVideoProfiles::route('/'),
            'create' => Pages\CreateVideoProfile::route('/create'),
            'edit' => Pages\EditVideoProfile::route('/{record}/edit'),
        ];
    }
}
