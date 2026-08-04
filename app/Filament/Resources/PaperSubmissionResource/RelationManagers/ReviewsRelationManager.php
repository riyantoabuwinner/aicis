<?php

namespace App\Filament\Resources\PaperSubmissionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reviewer_id')
                    ->relationship('reviewer', 'name')
                    ->required()
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Assigned' => 'Assigned',
                        'Reviewed' => 'Reviewed',
                    ])
                    ->required(),
                Forms\Components\Select::make('recommendation')
                    ->options([
                        'Accept' => 'Accept',
                        'Accept with Revision' => 'Accept with Revision',
                        'Reject' => 'Reject',
                    ]),
                Forms\Components\Textarea::make('comments_for_author')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('comments_for_admin')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reviewer.name')
            ->columns([
                Tables\Columns\TextColumn::make('reviewer.name')->label('Reviewer'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('recommendation')->badge()
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
            ->headerActions([
                // Create handled in PaperSubmissionResource via "Assign Reviewers" action
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
