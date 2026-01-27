<?php

namespace App\Filament\Resources\PengajuanPrResource\Pages;

use App\Filament\Resources\PengajuanPrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanPr extends EditRecord
{
    protected static string $resource = PengajuanPrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
