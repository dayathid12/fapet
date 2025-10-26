<?php

namespace App\Filament\Resources\BiayaBBMResource\Pages;

use App\Filament\Resources\BiayaBBMResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBiayaBBM extends EditRecord
{
    protected static string $resource = BiayaBBMResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
