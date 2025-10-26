<?php

namespace App\Filament\Resources\BiayaLainResource\Pages;

use App\Filament\Resources\BiayaLainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiayaLain extends EditRecord
{
    protected static string $resource = BiayaLainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
