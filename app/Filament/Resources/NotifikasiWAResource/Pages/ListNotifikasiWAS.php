<?php

namespace App\Filament\Resources\NotifikasiWAResource\Pages;

use App\Filament\Resources\NotifikasiWAResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotifikasiWAS extends ListRecords
{
    protected static string $resource = NotifikasiWAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
