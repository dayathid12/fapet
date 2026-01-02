<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateNomorSuratCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-nomor-surat-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all no_surat_tugas to null in perjalanans table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update no_surat_tugas to null...');

        $count = \App\Models\Perjalanan::whereNotNull('no_surat_tugas')->update(['no_surat_tugas' => null]);

        $this->info("Updated {$count} records. All no_surat_tugas have been set to null.");
    }
}
