<?php

namespace App\Filament\Resources\PengajuanPrResource\Pages;

use App\Filament\Resources\PengajuanPrResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengajuanPr extends CreateRecord
{
    protected static string $resource = PengajuanPrResource::class;

    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Ajukan')
            ->successRedirectUrl($this->getResource()::getUrl('index'));
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Ajukan dan Buat Baru');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create');
    }
}
