<?php

namespace App\Filament\Resources\BiayaLainResource\Pages;

use App\Filament\Resources\BiayaLainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayaLains extends ListRecords
{
    protected static string $resource = BiayaLainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
