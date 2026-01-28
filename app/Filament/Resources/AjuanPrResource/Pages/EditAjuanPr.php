<?php

namespace App\Filament\Resources\AjuanPrResource\Pages;

use App\Filament\Resources\AjuanPrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAjuanPr extends EditRecord
{
    protected static string $resource = AjuanPrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
