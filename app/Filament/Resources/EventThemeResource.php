<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventThemeResource\Pages;
use App\Models\EventTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventThemeResource extends Resource
{
    protected static ?int $navigationSort = 3;
    protected static ?string $model = EventTheme::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationGroup = 'Event Management';

    protected static ?string $navigationLabel = 'Theme';

    protected static ?string $modelLabel = 'Theme';

    protected static ?string $pluralModelLabel = 'Themes';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Theme Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('event-themes')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->hidden(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-m-eye')
                    ->iconButton()
                    ->url(fn (): string => url('theme'))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListEventThemes::route('/'),
            'create' => Pages\CreateEventTheme::route('/create'),
            'edit' => Pages\EditEventTheme::route('/{record}/edit'),
        ];
    }
}
