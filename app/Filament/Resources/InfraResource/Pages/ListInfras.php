<?php

namespace App\Filament\Resources\InfraResource\Pages;

use App\Filament\Resources\InfraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInfras extends ListRecords
{
    protected static string $resource = InfraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
