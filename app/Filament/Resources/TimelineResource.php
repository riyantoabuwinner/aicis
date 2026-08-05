<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimelineResource\Pages;
use App\Filament\Resources\TimelineResource\RelationManagers;
use App\Models\Timeline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimelineResource extends Resource
{
    protected static ?int $navigationSort = 10;
    protected static ?string $model = Timeline::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Content Management';

    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Timeline Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_from')
                            ->label('Start Date')
                            ->required(),
                        Forms\Components\DatePicker::make('date_until')
                            ->label('End Date')
                            ->nullable(),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->hidden(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_from')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_until')
                    ->label('End Date')
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-s-eye')
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
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
            'index' => Pages\ListTimelines::route('/'),
            'create' => Pages\CreateTimeline::route('/create'),
            'edit' => Pages\EditTimeline::route('/{record}/edit'),
        ];
    }
}
