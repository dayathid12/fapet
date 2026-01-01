<?php

namespace App\Filament\Resources\SuratTugasResource\Pages;

use App\Filament\Resources\SuratTugasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratTugas extends ListRecords
{
    protected static string $resource = SuratTugasResource::class;

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    protected function getHeaderActions(): array
    {
        return [
            // Removed CreateAction to hide the "New perjalanan kendaraan" button
        ];
    }
}
