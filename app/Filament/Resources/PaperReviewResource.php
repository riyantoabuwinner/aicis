<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaperReviewResource\Pages;
use App\Filament\Resources\PaperReviewResource\RelationManagers;
use App\Models\PaperReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaperReviewResource extends Resource
{
    protected static ?string $model = PaperReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = 'Peer Review';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['reviewer', 'admin', 'superadmin']);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = static::getModel()::query();
        if (auth()->user()->hasRole(['reviewer']) && !auth()->user()->hasRole(['admin', 'superadmin'])) {
            $query->where('reviewer_id', auth()->id());
        }
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('recommendation')
                    ->options([
                        'Accept' => 'Accept',
                        'Accept with Revision' => 'Accept with Revision',
                        'Reject' => 'Reject',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('comments_for_author')
                    ->label('Comments for Author')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('comments_for_admin')
                    ->label('Private Comments for Admin')
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('status')
                    ->default('Reviewed'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paperSubmission.title')
                    ->label('Paper Title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('recommendation')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Accept' => 'success',
                        'Accept with Revision' => 'warning',
                        'Reject' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPaperReviews::route('/'),
            'edit' => Pages\EditPaperReview::route('/{record}/edit'),
        ];
    }
}
