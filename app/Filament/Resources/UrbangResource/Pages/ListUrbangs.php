<?php

namespace App\Filament\Resources\UrbangResource\Pages;

use App\Filament\Resources\UrbangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUrbangs extends ListRecords
{
    protected static string $resource = UrbangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
