<?php

namespace App\Filament\Resources\SPTJBUangPengemudiResource\Pages;

use App\Filament\Resources\SPTJBUangPengemudiResource;
use App\Models\SPTJBUangPengemudiDetail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSPTJBUangPengemudi extends EditRecord
{
    protected static string $resource = SPTJBUangPengemudiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(), // Removed as per request
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        \Log::info('SPTJBUangPengemudi ID: ' . $data['id']);
        $totalJumlahUangDiterima = SPTJBUangPengemudiDetail::where('sptjb_pengemudi_id', $data['id'])->sum('jumlah_uang_diterima');
        \Log::info('Total Jumlah Uang Diterima: ' . $totalJumlahUangDiterima);
        $data['total_jumlah_uang_diterima'] = $totalJumlahUangDiterima;

        return $data;
    }
}
