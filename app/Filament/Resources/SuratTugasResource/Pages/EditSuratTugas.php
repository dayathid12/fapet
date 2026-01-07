<?php

namespace App\Filament\Resources\SuratTugasResource\Pages;

use App\Filament\Resources\SuratTugasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratTugas extends EditRecord
{
    protected static string $resource = SuratTugasResource::class;

    protected static bool $modal = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $perjalanan = $this->getRecord()->perjalanan;
        if ($perjalanan) {
            $data['no_surat_tugas'] = $perjalanan->no_surat_tugas;
            $data['upload_tte'] = $perjalanan->upload_tte;
            $data['upload_surat_tugas'] = $perjalanan->upload_surat_tugas;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract fields that belong to Perjalanan model
        $perjalananData = [
            'no_surat_tugas' => $data['no_surat_tugas'] ?? null,
            'upload_tte' => $data['upload_tte'] ?? null,
            'upload_surat_tugas' => $data['upload_surat_tugas'] ?? null,
        ];

        // Store in a temporary property to use in afterSave
        $this->perjalananData = $perjalananData;

        // Remove from data so they don't save to PerjalananKendaraan
        unset($data['no_surat_tugas'], $data['upload_tte'], $data['upload_surat_tugas']);

        return $data;
    }

    protected function afterSave(): void
    {
        $perjalanan = $this->getRecord()->perjalanan;
        if ($perjalanan && isset($this->perjalananData)) {
            $perjalanan->update($this->perjalananData);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
