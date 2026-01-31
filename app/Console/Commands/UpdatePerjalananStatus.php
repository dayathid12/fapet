<?php

namespace App\Console\Commands;

use App\Models\Perjalanan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdatePerjalananStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perjalanan:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status perjalanan to Selesai if waktu_kepulangan is today or earlier';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating perjalanan status...');

        $updatedCount = Perjalanan::where('status_perjalanan', 'Terjadwal')
            ->where('waktu_kepulangan', '<=', Carbon::today())
            ->update(['status_perjalanan' => 'Selesai']);

        $this->info("Updated {$updatedCount} perjalanan records to 'Selesai'.");

        return 0;
    }
}
