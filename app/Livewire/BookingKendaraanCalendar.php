<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Kendaraan; // Changed from Staf
use App\Models\Perjalanan;

class BookingKendaraanCalendar extends Component
{
    public $currentDate;
    public $selectedMonth;
    public $selectedYear;
    public $search = '';

    public $vehicles = []; // Changed from $drivers
    public $dates = [];
    public $perjalanansByVehicleAndDate = []; // Changed from $perjalanansByDriverAndDate

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

    public function updatedSearch()
    {
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

        // Fetch all vehicles, filtered by search term
        $queriedVehicles = Kendaraan::query() // Changed from Staf
            ->when($this->search, function ($query) {
                $query->where('merk_type', 'like', '%' . $this->search . '%')
                      ->orWhere('nopol_kendaraan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('merk_type')
            ->orderBy('nopol_kendaraan')
            ->get();

        $this->vehicles = $queriedVehicles
            ->map(function ($vehicle) {
                return [
                    'nopol_kendaraan' => $vehicle->nopol_kendaraan,
                    'merk_type' => $vehicle->merk_type,
                    'jenis_kendaraan' => $vehicle->jenis_kendaraan,
                ];
            })->values();

        // Fetch Perjalanan records for the selected month/year
        // Eager load 'kendaraan' relationship to access details
        $perjalanans = Perjalanan::query()
            ->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
            ->whereIn('status_perjalanan', ['Terjadwal', 'Selesai'])
            ->with(['kendaraan', 'wilayah'])
            ->get();

        // Initialize perjalanansByVehicleAndDate
        $this->perjalanansByVehicleAndDate = [];
        foreach ($this->vehicles as $vehicle) {
            foreach ($this->dates as $date) {
                $this->perjalanansByVehicleAndDate[$vehicle['nopol_kendaraan']][$date] = [];
            }
        }

        // Populate perjalanansByVehicleAndDate
        foreach ($perjalanans as $perjalanan) {
            // Check if perjalanan has any associated vehicles
            if ($perjalanan->kendaraan->isNotEmpty()) {
                $startDate = Carbon::parse($perjalanan->waktu_keberangkatan)->startOfDay();
                $endDate = Carbon::parse($perjalanan->waktu_kepulangan)->endOfDay();

                foreach ($perjalanan->kendaraan as $kendaraan) { // Loop through associated vehicles
                    $vehicleNopol = $kendaraan->nopol_kendaraan;

                    $currentPerjalananDay = $startDate->copy();
                    while ($currentPerjalananDay->lte($endDate)) {
                        $dateKey = $currentPerjalananDay->format('Y-m-d');

                        // Ensure the date is within the currently displayed month and the vehicle exists
                        if (isset($this->perjalanansByVehicleAndDate[$vehicleNopol][$dateKey])) {
                            $this->perjalanansByVehicleAndDate[$vehicleNopol][$dateKey][] = [
                                'nomor_perjalanan' => $perjalanan->nomor_perjalanan,
                                'merk_type' => $kendaraan->merk_type ?? 'N/A',
                                'nopol_kendaraan' => $kendaraan->nopol_kendaraan ?? 'N/A',
                                'waktu_keberangkatan' => Carbon::parse($perjalanan->waktu_keberangkatan)->format('d M Y H:i'),
                                'waktu_kepulangan' => Carbon::parse($perjalanan->waktu_kepulangan)->format('d M Y H:i'),
                                'kota_kabupaten' => $perjalanan->wilayah->nama_wilayah ?? $perjalanan->alamat_tujuan,
                                'status_perjalanan' => $perjalanan->status_perjalanan,
                            ];
                        }
                        $currentPerjalananDay->addDay();
                    }
                }
            }
        }
    }

    public function render()
    {
        $years = range(Carbon::now()->year - 5, Carbon::now()->year + 5);

        return view('livewire.booking-kendaraan-calendar', [
            'years' => $years,
        ]);
    }
}
