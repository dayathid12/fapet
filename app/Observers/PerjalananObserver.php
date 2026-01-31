<?php

namespace App\Observers;

use App\Models\Perjalanan;
use Carbon\Carbon;

class PerjalananObserver
{
    /**
     * Handle the Perjalanan "saving" event.
     *
     * @param  \App\Models\Perjalanan  $perjalanan
     * @return void
     */
    public function saving(Perjalanan $perjalanan): void
    {
        if ($perjalanan->isDirty('no_surat_tugas') && !empty($perjalanan->no_surat_tugas)) {
            $perjalanan->tgl_input_surat_tugas = Carbon::now();
        }

        if ($perjalanan->isDirty('upload_surat_tugas') && !empty($perjalanan->upload_surat_tugas)) {
            $perjalanan->tgl_upload_surat_tugas = Carbon::now();
        }
    }
}
