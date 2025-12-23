<?php

namespace App\Filament\Resources\KondisiBanResource\Pages;

use App\Filament\Resources\KondisiBanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKondisiBan extends CreateRecord
{
    protected static string $resource = KondisiBanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (filled(request()->query('nopol_kendaraan'))) {
            $data['nopol_kendaraan'] = request()->query('nopol_kendaraan');
        }

        return $data;
    }
}