<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Perjalanan;
use Carbon\Carbon;

class UpdateTripStatusCommand extends Command
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
    protected $description = 'Update trip status from Terjadwal to Selesai based on waktu_kepulangan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $tripsToUpdate = Perjalanan::where('status_perjalanan', 'Terjadwal')
            ->where('waktu_kepulangan', '<=', $now)
            ->get();

        if ($tripsToUpdate->isEmpty()) {
            $this->info('Tidak ada perjalanan dengan status Terjadwal yang waktu kepulangannya telah terlewati.');
            return;
        }

        foreach ($tripsToUpdate as $trip) {
            $trip->status_perjalanan = 'Selesai';
            $trip->save();
            $this->info("Status perjalanan {$trip->id} berhasil diubah menjadi Selesai.");
        }

        $this->info('Proses pembaruan status perjalanan selesai.');
    }
}
