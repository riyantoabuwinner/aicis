<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Static Pages';
    protected static ?string $modelLabel = 'Static Page';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['superadmin', 'admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Main Information')->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])->columns(2),
                    
                    Forms\Components\Section::make('Content')->schema([
                        Forms\Components\Toggle::make('is_html')
                            ->label('HTML Mode (Write HTML Code)')
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                if ($state) {
                                    $set('content_html', $get('content'));
                                } else {
                                    $set('content', $get('content_html'));
                                }
                            }),
                        \Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor::make('content')
                            ->label('Content Body')
                            ->hidden(fn (Forms\Get $get) => $get('is_html') === true)
                            ->fileAttachmentsDirectory('pages')
                            ->minHeight(400)
                            ->showMenuBar(),
                        Forms\Components\Textarea::make('content_html')
                            ->label('HTML Source')
                            ->hidden(fn (Forms\Get $get) => $get('is_html') !== true)
                            ->rows(30)
                            ->formatStateUsing(fn ($record) => $record?->content),
                    ]),
                ])->columnSpan(['sm' => 2]),
                
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Page Settings')->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Is Active / Published')
                            ->default(true),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('pages'),
                    ]),
                ])->columnSpan(['sm' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
