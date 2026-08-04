<?php

namespace App\Filament\Imports;

use App\Models\OfficialPartner;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class OfficialPartnerImporter extends Importer
{
    protected static ?string $model = OfficialPartner::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('logo_url')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('url')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('is_active')
                ->boolean()
                ->rules(['boolean']),
        ];
    }

    public function resolveRecord(): ?OfficialPartner
    {
        // return OfficialPartner::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new OfficialPartner();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your official partner import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
