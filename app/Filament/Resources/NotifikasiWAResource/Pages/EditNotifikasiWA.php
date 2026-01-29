<?php

namespace App\Filament\Resources\NotifikasiWAResource\Pages;

use App\Filament\Resources\NotifikasiWAResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotifikasiWA extends EditRecord
{
    protected static string $resource = NotifikasiWAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
