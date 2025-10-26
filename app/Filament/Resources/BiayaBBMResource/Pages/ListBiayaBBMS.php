<?php

namespace App\Filament\Resources\BiayaBBMResource\Pages;

use App\Filament\Resources\BiayaBBMResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayaBBMS extends ListRecords
{
    protected static string $resource = BiayaBBMResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
