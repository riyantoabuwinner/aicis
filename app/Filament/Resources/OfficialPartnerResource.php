<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficialPartnerResource\Pages;
use App\Models\OfficialPartner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OfficialPartnerResource extends Resource
{
    protected static ?int $navigationSort = 9;
    protected static ?string $model = OfficialPartner::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Content Management';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Partner Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logo Upload (Optional)')
                            ->image()
                            ->directory('official-partners')
                            ->nullable(),
                        Forms\Components\TextInput::make('logo_url')
                            ->label('Logo URL (If no upload)')
                            ->url()
                            ->nullable()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('url')
                            ->label('Website URL')
                            ->url()
                            ->nullable()
                            ->maxLength(255),
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
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->getStateUsing(function ($record) {
                        return $record->logo_path ? $record->logo_path : $record->logo_url;
                    })
                    ->square(),
                Tables\Columns\TextColumn::make('logo_url')
                    ->label('Logo URL')
                    ->limit(40)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('Website')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListOfficialPartners::route('/'),
            'create' => Pages\CreateOfficialPartner::route('/create'),
            'edit' => Pages\EditOfficialPartner::route('/{record}/edit'),
        ];
    }
}
