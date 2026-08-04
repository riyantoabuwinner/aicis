<?php

namespace App\Filament\Resources\MyPaperSubmissionResource\Pages;

use App\Filament\Resources\MyPaperSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyPaperSubmission extends CreateRecord
{
    use \Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

    protected static string $resource = MyPaperSubmissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'Unassigned'; // Default status when submitted via Wizard
        return $data;
    }

    protected function getSteps(): array
    {
        return [
            \Filament\Forms\Components\Wizard\Step::make('Start')
                ->description('Select track & agreements')
                ->schema([
                    \Filament\Forms\Components\Hidden::make('user_id')
                        ->default(fn () => \Illuminate\Support\Facades\Auth::id()),
                    \Filament\Forms\Components\Select::make('conference_id')
                        ->relationship('conference', 'name')
                        ->required()
                        ->default(function () {
                            return \App\Models\Conference::where('is_active', true)->first()?->id;
                        }),
                    \Filament\Forms\Components\Select::make('conference_track_id')
                        ->relationship('track', 'name')
                        ->label('Category / Track')
                        ->required(),
                    \Filament\Forms\Components\CheckboxList::make('agreements')
                        ->label('Submission Agreements')
                        ->options([
                            'original' => 'I confirm this submission is original and not under consideration elsewhere.',
                            'guidelines' => 'I have read and followed the Author Guidelines and Template.',
                            'double_blind' => 'I have removed author identifying information from the manuscript (for double-blind review).',
                        ])
                        ->required()
                        ->rules([
                            function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    if (count($value) !== 3) {
                                        $fail('You must agree to all conditions before proceeding.');
                                    }
                                };
                            },
                        ])
                        ->dehydrated(false) // Don't save this field to DB
                ]),
            \Filament\Forms\Components\Wizard\Step::make('Upload Submission')
                ->description('Upload your manuscript file')
                ->schema([
                    \Filament\Forms\Components\FileUpload::make('full_paper_path')
                        ->label('Manuscript File (.docx or .pdf)')
                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->required(),
                ]),
            \Filament\Forms\Components\Wizard\Step::make('Metadata')
                ->description('Title, Abstract, Keywords & Co-Authors')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    \Filament\Forms\Components\Textarea::make('abstract')
                        ->required()
                        ->columnSpanFull(),
                    \Filament\Forms\Components\TextInput::make('keywords')
                        ->placeholder('Separate with commas')
                        ->maxLength(255)
                        ->columnSpanFull(),
                    \Filament\Forms\Components\Repeater::make('co_authors')
                        ->label('Co-Authors (Optional)')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('name')->required(),
                            \Filament\Forms\Components\TextInput::make('email')->email()->required(),
                            \Filament\Forms\Components\TextInput::make('affiliation')->required(),
                        ])
                        ->columnSpanFull()
                        ->defaultItems(0)
                        ->addActionLabel('Add Co-Author'),
                ]),
            \Filament\Forms\Components\Wizard\Step::make('Supplementary Files')
                ->description('Optional supporting data')
                ->schema([
                    \Filament\Forms\Components\FileUpload::make('supplementary_file_path')
                        ->label('Supplementary File (ZIP, PDF, etc)')
                        ->helperText('Upload any supporting documents, data sets, or originality statements.')
                        ->acceptedFileTypes(['application/pdf', 'application/zip', 'application/x-zip-compressed']),
                    \Filament\Forms\Components\FileUpload::make('presentation_file_path')
                        ->label('Presentation File (PPTX/PDF)')
                        ->acceptedFileTypes(['application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation']),
                ]),
        ];
    }
}
