<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Tabs;

class PlagiarismChecker extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';
    protected static ?string $navigationLabel = 'Plagiarism Checker';
    protected static ?string $navigationGroup = 'Tools';
    protected static ?int $navigationSort = 59;
    protected static string $view = 'filament.pages.plagiarism-checker';

    public ?array $data = [];
    public bool $isScanning = false;
    public bool $scanComplete = false;
    public ?int $similarityScore = null;
    public ?int $scannedWords = null;
    public ?int $matchedSources = null;
    public ?float $scanDuration = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Input Method')
                    ->tabs([
                        Tabs\Tab::make('Upload Document')
                            ->icon('heroicon-o-document-arrow-up')
                            ->schema([
                                FileUpload::make('document')
                                    ->label('Upload File (PDF/DOCX)')
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->maxSize(10240)
                                    ->requiredWithout('text_content'),
                            ]),
                        Tabs\Tab::make('Paste Text')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Textarea::make('text_content')
                                    ->label('Text Content')
                                    ->rows(10)
                                    ->requiredWithout('document'),
                            ]),
                    ])->columnSpanFull()
            ])
            ->statePath('data');
    }

    public function startScan()
    {
        $this->form->validate();

        $this->isScanning = true;
        $this->scanComplete = false;
    }

    public function downloadReport()
    {
        $data = [
            'score' => $this->similarityScore,
            'words' => $this->scannedWords,
            'sources' => $this->matchedSources,
            'duration' => $this->scanDuration
        ];
        
        session()->put('plagiarism_report', $data);
        return redirect()->to(route('plagiarism.report'));
    }

    public function completeScan()
    {
        $this->isScanning = false;
        $this->scanComplete = true;
        // Generate a random mock score and stats
        $this->similarityScore = rand(5, 28);
        $this->scannedWords = rand(2500, 8500);
        $this->matchedSources = rand(2, 15);
        $this->scanDuration = rand(12, 45) / 10;
    }
}
