<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\Hashtag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $modelLabel = 'Post';

    
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
                            ->fileAttachmentsDirectory('gallery')
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
                    Forms\Components\Section::make('Publication Settings')->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'Draft' => 'Draft',
                                'Published' => 'Published',
                                'Archived' => 'Archived',
                            ])
                            ->default('Draft')
                            ->required(),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now()),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Category')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                Forms\Components\TextInput::make('slug')->required(),
                            ]),
                        Forms\Components\TagsInput::make('hashtags')
                            ->label('Hashtag')
                            ->suggestions(fn () => Hashtag::pluck('name')->toArray()),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('gallery')
                            ->helperText('This image will be automatically added to the Gallery.'),
                    ]),
                ])->columnSpan(['sm' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('title')->limit(80)->alignLeft(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpan(3),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Published' => 'Published',
                    ])
                    ->columnSpan(1),
                Tables\Filters\Filter::make('published_from')
                    ->columnSpan(2)
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->label('From'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['date'],
                            fn ($query, $date) => $query->whereDate('published_at', '>=', $date),
                        );
                    }),
                Tables\Filters\Filter::make('published_until')
                    ->columnSpan(2)
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['date'],
                            fn ($query, $date) => $query->whereDate('published_at', '<=', $date),
                        );
                    }),
                Tables\Filters\Filter::make('search_filter')
                    ->columnSpan(2)
                    ->form([
                        Forms\Components\TextInput::make('search')
                            ->label(new \Illuminate\Support\HtmlString('&nbsp;'))
                            ->placeholder('Search title...')
                            ->prefixIcon('heroicon-m-magnifying-glass'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['search'],
                            fn ($query, $search) => $query->where('title', 'like', "%{$search}%")
                        );
                    }),
                Tables\Filters\Filter::make('reset_filters')
                    ->label(new \Illuminate\Support\HtmlString('&nbsp;'))
                    ->columnSpan(2)
                    ->form([
                        \Filament\Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('reset')
                                ->label('Reset')
                                ->color('danger')
                                ->button()
                                ->extraAttributes(['class' => 'w-full'])
                                ->action(function ($livewire) {
                                    $livewire->resetTableFiltersForm();
                                }),
                        ]),
                    ])
                    ->query(fn ($query) => $query),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(12)
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-s-eye')
                    ->color('gray')
                    ->iconButton()
                    ->url(fn ($record) => url('/post/' . $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
