<?php

namespace App\Filament\Resources\BiayaTolResource\Pages;

use App\Filament\Resources\BiayaTolResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiayaTol extends EditRecord
{
    protected static string $resource = BiayaTolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
