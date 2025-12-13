<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Perjalanan;
use Carbon\Carbon;

class UpdateTripStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trip:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update trip statuses from "Terjadwal" to "Selesai" if waktu_kepulangan is in the past.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for trips to update...');

        $updatedCount = Perjalanan::where('status_perjalanan', 'Terjadwal')
                                  ->where('waktu_kepulangan', '<', Carbon::now())
                                  ->update(['status_perjalanan' => 'Selesai']);

        $this->info("Updated {$updatedCount} trip(s) from 'Terjadwal' to 'Selesai'.");
    }
}
