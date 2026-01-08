<?php

namespace App\Filament\Resources\SPTJBUangPengemudiResource\Pages;

use App\Filament\Resources\SPTJBUangPengemudiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSPTJBUangPengemudi extends CreateRecord
{
    protected static string $resource = SPTJBUangPengemudiResource::class;

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
