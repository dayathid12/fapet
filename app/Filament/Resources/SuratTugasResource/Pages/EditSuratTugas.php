<?php

namespace App\Filament\Resources\SuratTugasResource\Pages;

use App\Filament\Resources\SuratTugasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratTugas extends EditRecord
{
    protected static string $resource = SuratTugasResource::class;

    protected static bool $modal = false;

    protected array $perjalananData;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    // protected function getFormActions(): array
    // {
    //     return [
    //         Actions\Action::make('save')
    //             ->label('Save changes')
    //             ->action('save')
    //             ->color('primary'),
    //         Actions\Action::make('cancel')
    //             ->label('Cancel')
    //             ->url(fn () => $this->getResource()::getUrl('index'))
    //             ->color('gray'),
    //     ];
    // }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $perjalanan = $this->getRecord()->perjalanan;
        if ($perjalanan) {
            $data['no_surat_tugas'] = $perjalanan->no_surat_tugas;
            $data['upload_tte'] = $perjalanan->upload_tte;
            $data['upload_surat_tugas'] = $perjalanan->upload_surat_tugas;
            // Add waktu_keberangkatan and waktu_kepulangan for the form
            $data['perjalanan']['waktu_keberangkatan'] = $perjalanan->waktu_keberangkatan;
            $data['perjalanan']['waktu_kepulangan'] = $perjalanan->waktu_kepulangan;
            // Add new fields from Perjalanan for the form
            $data['perjalanan']['unit_kerja_fakultas_ukm'] = $perjalanan->unit_kerja_fakultas_ukm;
            $data['perjalanan']['nama_kegiatan'] = $perjalanan->nama_kegiatan;
            $data['perjalanan']['lokasi_keberangkatan'] = $perjalanan->lokasi_keberangkatan;
            $data['perjalanan']['alamat_tujuan'] = $perjalanan->alamat_tujuan;
            $data['perjalanan']['kota_kabupaten'] = $perjalanan->kota_kabupaten;
            $data['perjalanan']['tanggal_surat_tugas'] = $perjalanan->tanggal_surat_tugas;
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
            'waktu_keberangkatan' => $data['perjalanan']['waktu_keberangkatan'] ?? null,
            'waktu_kepulangan' => $data['perjalanan']['waktu_kepulangan'] ?? null,
            'tanggal_surat_tugas' => $data['perjalanan']['tanggal_surat_tugas'] ?? null,
        ];

        // Store in a temporary property to use in afterSave
        $this->perjalananData = $perjalananData;

        // Remove from data so they don't save to PerjalananKendaraan
        unset($data['no_surat_tugas'], $data['upload_tte'], $data['upload_surat_tugas']);
        unset($data['perjalanan']['waktu_keberangkatan'], $data['perjalanan']['waktu_kepulangan'], $data['perjalanan']['tanggal_surat_tugas']);
        // Unset the whole 'perjalanan' array if it's empty after unsetting its elements
        if (empty($data['perjalanan'])) {
            unset($data['perjalanan']);
        }

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
