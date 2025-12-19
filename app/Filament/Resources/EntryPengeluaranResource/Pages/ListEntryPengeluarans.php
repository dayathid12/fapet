<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntryPengeluarans extends ListRecords
{
    protected static string $resource = EntryPengeluaranResource::class;

    public function getTitle(): string
    {
        return static::getResource()::getNavigationLabel();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
