<?php

namespace App\Filament\Resources\BiayaTolResource\Pages;

use App\Filament\Resources\BiayaTolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayaTols extends ListRecords
{
    protected static string $resource = BiayaTolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
