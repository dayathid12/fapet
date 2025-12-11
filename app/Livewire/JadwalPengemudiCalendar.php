<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Staf;
use App\Models\Perjalanan; // Add Perjalanan model
// use App\Models\Kendaraan; // Kendaraan will be accessed via Perjalanan->kendaraan
// use App\Models\PerjalananKendaraan; // PerjalananKendaraan will be accessed via Perjalanan->details
// use App\Models\JadwalPengemudi; // JadwalPengemudi is no longer directly used

class JadwalPengemudiCalendar extends Component
{
    public $currentDate; // Keep for internal date manipulation if needed
    public $selectedMonth;
    public $selectedYear;

    public $drivers = []; // All drivers
    public $dates = []; // All dates in the selected month/year
    public $perjalanansByDriverAndDate = []; // Pivoted data

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->selectedMonth = $this->currentDate->month;
        $this->selectedYear = $this->currentDate->year;
        $this->loadPerjalananData();
    }

    public function updatedSelectedMonth($value)
    {
        $this->selectedMonth = $value;
        $this->loadPerjalananData();
    }

    public function updatedSelectedYear($value)
    {
        $this->selectedYear = $value;
        $this->loadPerjalananData();
    }

    public function loadPerjalananData()
    {
        $startOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfDay();
        $endOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth()->endOfDay();

        // Populate $this->dates with all days of the month
        $this->dates = collect();
        $currentDay = $startOfMonth->copy();
        while ($currentDay->lte($endOfMonth)) {
            $this->dates->push($currentDay->format('Y-m-d'));
            $currentDay->addDay();
        }

        // Fetch all drivers
        $this->drivers = Staf::orderBy('nama_staf')->get()->map(function ($staf) {
            return ['staf_id' => $staf->staf_id, 'nama_staf' => $staf->nama_staf];
        })->values();
        
        // Fetch Perjalanan records for the selected month/year
        $perjalanans = Perjalanan::query()
            ->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
            ->with(['pengemudi', 'kendaraan', 'wilayah'])
            ->get();

        // Initialize perjalanansByDriverAndDate
        $this->perjalanansByDriverAndDate = [];
        foreach ($this->drivers as $driver) {
            foreach ($this->dates as $date) {
                $this->perjalanansByDriverAndDate[$driver['staf_id']][$date] = [];
            }
        }

        // Populate perjalanansByDriverAndDate
        foreach ($perjalanans as $perjalanan) {
            if ($perjalanan->pengemudi->isNotEmpty() && $perjalanan->kendaraan->isNotEmpty()) {
                foreach ($perjalanan->pengemudi as $pengemudi) {
                    $driverId = $pengemudi->staf_id;
                    $date = Carbon::parse($perjalanan->waktu_keberangkatan)->format('Y-m-d');

                    if (isset($this->perjalanansByDriverAndDate[$driverId][$date])) {
                        $this->perjalanansByDriverAndDate[$driverId][$date][] = [
                            'nomor_perjalanan' => $perjalanan->nomor_perjalanan,
                            'merk_type' => $perjalanan->kendaraan->first()->merk_type ?? 'N/A',
                            'nopol_kendaraan' => $perjalanan->kendaraan->first()->nopol_kendaraan ?? 'N/A',
                            'waktu_keberangkatan' => Carbon::parse($perjalanan->waktu_keberangkatan)->format('d M Y H:i'),
                            'waktu_kepulangan' => Carbon::parse($perjalanan->waktu_kepulangan)->format('d M Y H:i'),
                            'kota_kabupaten' => $perjalanan->wilayah->nama_wilayah ?? $perjalanan->alamat_tujuan,
                        ];
                    }
                }
            }
        }
        
        // No need to sort drivers here, as they are fetched sorted by nama_staf
        // No need to sort dates here, as they are generated chronologically
    }

    public function render()
    {
        $years = range(Carbon::now()->year - 5, Carbon::now()->year + 5);

        return view('livewire.jadwal-pengemudi-calendar', [
            'years' => $years,
        ]);
    
}
}
