<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTripStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-trip-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update trip statuses from Terjadwal to Selesai if return time is today or earlier';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = \Carbon\Carbon::today();

        $updated = \App\Models\Perjalanan::where('status_perjalanan', 'Terjadwal')
            ->where('waktu_kepulangan', '<=', $today)
            ->update(['status_perjalanan' => 'Selesai']);

        $this->info("Updated {$updated} trip statuses to Selesai.");
    }
}
