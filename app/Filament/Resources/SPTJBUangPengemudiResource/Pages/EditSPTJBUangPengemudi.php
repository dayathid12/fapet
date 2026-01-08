<?php

namespace App\Filament\Resources\SPTJBUangPengemudiResource\Pages;

use App\Filament\Resources\SPTJBUangPengemudiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSPTJBUangPengemudi extends EditRecord
{
    protected static string $resource = SPTJBUangPengemudiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
