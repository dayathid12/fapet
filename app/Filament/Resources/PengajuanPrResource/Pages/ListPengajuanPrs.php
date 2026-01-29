<?php

namespace App\Filament\Resources\PengajuanPrResource\Pages;

use App\Filament\Resources\PengajuanPrResource;
use App\Models\PengajuanPr;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Livewire\WithFileUploads;

class ListPengajuanPrs extends ListRecords
{
    use WithFileUploads;

    protected static string $resource = PengajuanPrResource::class;

    protected static string $view = 'filament.resources.pengajuan-pr-resource.pages.list-pengajuan-prs';

    protected static bool $shouldMaximize = true;

    protected function getDefaultSortColumn(): ?string
    {
        return 'id';
    }

    public $showEditModal = false;
    public $selectedRecord = null;
    public $nomor_pr = '';
    public $proses_pr_screenshots = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Pengajuan')
                ->icon('heroicon-s-plus')
                ->size('lg')
                ->button(),
        ];
    }

    /**
     * Define the CreateAction for this page, used in custom Blade views.
     */
    public function getCreateAction(): Actions\CreateAction
    {
        return Actions\CreateAction::make()
            ->label('Buat Pengajuan') // Consistent with getHeaderActions
            ->icon('heroicon-s-plus') // Consistent with getHeaderActions
            ->size('lg'); // Consistent with getHeaderActions
    }

    public function openEditModal($recordId)
    {
        $this->selectedRecord = PengajuanPr::find($recordId);
        $this->nomor_pr = $this->selectedRecord->nomor_pr ?? '';
        $this->proses_pr_screenshots = [];
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedRecord = null;
        $this->nomor_pr = '';
        $this->proses_pr_screenshots = [];
    }

    public function saveEdit()
    {
        $this->validate([
            'nomor_pr' => 'nullable|string|max:255',
            'proses_pr_screenshots.*' => 'nullable|image|max:5120',
        ]);

        $this->selectedRecord->update([
            'nomor_pr' => $this->nomor_pr,
        ]);

        if ($this->proses_pr_screenshots) {
            $existingScreenshots = $this->selectedRecord->proses_pr_screenshots ?? [];
            $newScreenshots = [];

            foreach ($this->proses_pr_screenshots as $file) {
                $path = $file->store('pengajuan-pr-screenshots');
                $newScreenshots[] = $path;
            }

            $this->selectedRecord->update([
                'proses_pr_screenshots' => array_merge($existingScreenshots, $newScreenshots),
            ]);
        }

        $this->closeEditModal();
        $this->notify('success', 'Data berhasil diperbarui.');
    }

    public function downloadFiles($recordId)
    {
        $record = PengajuanPr::find($recordId);
        $files = $record->upload_files ?? [];

        if (empty($files)) {
            $this->notify('warning', 'Tidak ada file untuk diunduh.');
            return;
        }

        $zipFileName = 'pengajuan-pr-' . $record->id . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($file));
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
