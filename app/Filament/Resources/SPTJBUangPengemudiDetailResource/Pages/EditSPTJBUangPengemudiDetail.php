<?php

namespace App\Filament\Resources\SPTJBUangPengemudiDetailResource\Pages;

use App\Filament\Resources\SPTJBUangPengemudiDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSPTJBUangPengemudiDetail extends EditRecord
{
    protected static string $resource = SPTJBUangPengemudiDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
