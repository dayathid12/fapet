<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Staf;
use App\Models\Perjalanan; // Add Perjalanan model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// use App\Models\Kendaraan; // Kendaraan will be accessed via Perjalanan->kendaraan
// use App\Models\PerjalananKendaraan; // PerjalananKendaraan will be accessed via Perjalanan->details
// use App\Models\JadwalPengemudi; // JadwalPengemudi is no longer directly used

class JadwalPengemudiCalendar extends Component
{
    public $currentDate; // Keep for internal date manipulation if needed
    public $selectedMonth;
    public $selectedYear;
    public $search = ''; // Added this line

    public $drivers = []; // All drivers
    public $dates = []; // All dates in the selected month/year
    public $perjalanansByDriverAndDate = []; // Pivoted data

    public $manualSortOrder = []; // New property for manual sorting

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->selectedMonth = $this->currentDate->month;
        $this->selectedYear = $this->currentDate->year;
        $this->loadPerjalananData();
    }

    #[On('update-staf-sort')]
    public function updateStafSort($newOrder)
    {
        foreach ($newOrder as $index => $stafId) {
            Staf::where('staf_id', $stafId)->update(['sort_order' => $index]);
        }
        $this->manualSortOrder = $newOrder;
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

        // Fetch all drivers, filtered by search term
        $queriedDrivers = Staf::query() // Modified this line to use query()
            ->when($this->search, function ($query) { // Added this section for search
                $query->where('nama_staf', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order') // Order by the new sort_order column
            ->orderBy('nama_staf')  // Secondary sort for consistency
            ->get();

        $this->drivers = $queriedDrivers
            ->map(function ($staf) {
                return ['staf_id' => $staf->staf_id, 'nama_staf' => $staf->nama_staf, 'nip' => $staf->nip_staf];
            })->values();
        
        // Initialize manualSortOrder from the fetched database order
        $this->manualSortOrder = $queriedDrivers->pluck('staf_id')->toArray();
        
        // Fetch Perjalanan records for the selected month/year
        $perjalanans = Perjalanan::query()
            ->when(Auth::check() && Auth::user()->staf_id, function ($query) {
                dd(Auth::user()->staf_id);
                $query->whereHas('pengemudi', function ($subQuery) {
                    $subQuery->where('staf_id', Auth::user()->staf_id);
                });
            })
            ->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
            ->whereIn('status_perjalanan', ['Terjadwal', 'Selesai']) // Added this filter
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
                $startDate = Carbon::parse($perjalanan->waktu_keberangkatan)->startOfDay();
                $endDate = Carbon::parse($perjalanan->waktu_kepulangan)->endOfDay(); // Use endOfDay to include the end day

                foreach ($perjalanan->pengemudi as $pengemudi) {
                    $driverId = $pengemudi->staf_id;

                    $currentPerjalananDay = $startDate->copy();
                    while ($currentPerjalananDay->lte($endDate)) {
                        $dateKey = $currentPerjalananDay->format('Y-m-d');

                        // Ensure the date is within the currently displayed month and the driver exists
                        if (isset($this->perjalanansByDriverAndDate[$driverId][$dateKey])) {
                            $this->perjalanansByDriverAndDate[$driverId][$dateKey][] = [
                                'id' => $perjalanan->id, // Add the ID here
                                'nomor_perjalanan' => $perjalanan->nomor_perjalanan,
                                'merk_type' => $perjalanan->kendaraan->first()->merk_type ?? 'N/A',
                                'nopol_kendaraan' => $perjalanan->kendaraan->first()->nopol_kendaraan ?? 'N/A',
                                'waktu_keberangkatan' => Carbon::parse($perjalanan->waktu_keberangkatan)->format('d M Y H:i'),
                                'waktu_kepulangan' => Carbon::parse($perjalanan->waktu_kepulangan)->format('d M Y H:i'),
                                'kota_kabupaten' => $perjalanan->wilayah->nama_wilayah ?? $perjalanan->alamat_tujuan,
                            ];
                        }
                        $currentPerjalananDay->addDay();
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
