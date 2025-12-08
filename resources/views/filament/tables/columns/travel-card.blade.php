@php
    $record = $getRecord();

    // Data Mapping & Formatting
    $departureTime = \Carbon\Carbon::parse($record->waktu_keberangkatan);
    $returnTime = \Carbon\Carbon::parse($record->waktu_kepulangan);

    $day = $departureTime->format('d');
    $month = strtoupper($departureTime->translatedFormat('M')); // e.g., DES
    $startTime = $departureTime->format('H:i');
    $returnTimeDisplay = $returnTime->format('H:i');
    $returnDate = $returnTime->translatedFormat('d M');

    // Calculate isOvernight
    $isOvernight = $departureTime->diffInDays($returnTime) >= 1;

    // Calculate duration in days/nights
    $diffDays = $departureTime->diffInDays($returnTime);
    $duration = '1 Hari';
    if ($diffDays > 0) {
        $duration = ($diffDays + 1) . ' Hari'; // +1 because 0 diffInDays means 1 day
        if ($diffDays > 0) { // If it's more than a single day
            $duration .= ' ' . $diffDays . ' Malam';
        }
    }


    $detail = $record->details->first();
    $driverName = $detail?->pengemudi?->nama_staf ?? '-';
    $vehicleName = $detail?->kendaraan?->merk_type ?? 'Menunggu Unit';
    $plateNumber = $detail?->kendaraan?->nopol_kendaraan ?? '-';
    $picName = $record->nama_pengguna ?? '-'; // From main record

    // Status mapping from the React example
    $statusType = match ($record->status_perjalanan) {
        'Terjadwal' => 'scheduled',
        'Menunggu Persetujuan' => 'pending',
        'Ditolak' => 'rejected',
        'Selesai' => 'done',
        'Berjalan' => 'scheduled', // Assuming "Berjalan" is visually similar to "Terjadwal"
        default => 'pending', // Default to pending if unknown
    };

    // Styling based on statusType
    $decorativeLineColor = match ($statusType) {
        'scheduled' => 'bg-indigo-500',
        'pending'   => 'bg-amber-400',
        'done'      => 'bg-emerald-500',
        default     => 'bg-gray-300',
    };

    $statusBadgeClasses = match ($statusType) {
        'scheduled' => 'bg-blue-50 text-blue-600 border-blue-100',
        'pending'   => 'bg-amber-50 text-amber-600 border-amber-100',
        'rejected'  => 'bg-red-50 text-red-600 border-red-100',
        'done'      => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        default     => 'bg-gray-50 text-gray-600 border-gray-100',
    };

    // Driver Initials
    $driverInitials = ($driverName !== '-') ? collect(explode(' ', $driverName))->map(fn($n) => substr($n, 0, 1))->slice(0, 2)->implode('') : '-';
    if($driverInitials == '') { // Handle empty names
        $driverInitials = '-';
    }

@endphp

<div class="group bg-white rounded-3xl pl-7 p-5 border border-gray-100 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 relative overflow-hidden">

    {{-- Decorative Status Line --}}
    <div class="absolute top-0 left-0 bottom-0 w-1.5 {{ $decorativeLineColor }}"></div>

    <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center relative">

        {{-- 1. DATE, TIME & STATUS BLOCK --}}
        <div class="flex flex-col gap-3 pr-6 lg:border-r border-gray-100 min-w-[240px]">

            {{-- Status Positioned Above Date --}}
            <div>
                <span class="px-3 py-1 rounded-lg text-xs font-bold tracking-wider uppercase border {{ $statusBadgeClasses }} flex items-center gap-2 w-fit">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span>
                    {{ $record->status_perjalanan }}
                </span>
            </div>

            <div class="flex flex-row items-start gap-5">
                {{-- Calendar Widget --}}
                <div class="flex flex-col items-center w-[72px] bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden shrink-0 group-hover:border-indigo-200 transition-colors">
                    <div class="w-full bg-indigo-100 text-indigo-700 text-[11px] font-bold uppercase py-1.5 text-center tracking-widest border-b border-gray-100">
                        {{ $month }}
                    </div>
                    <div class="py-2 text-4xl font-extrabold text-gray-900 tracking-tighter">
                        {{ $day }}
                    </div>
                </div>

                {{-- Time Details with Duration Line --}}
                <div class="flex flex-col relative h-full justify-between py-1 w-full min-h-[80px]">
                    {{-- Connecting Line --}}
                    <div class="absolute left-[7px] top-3 bottom-3 w-0.5 bg-gray-200 rounded-full"></div>

                    {{-- Departure --}}
                    <div class="relative pl-5 mb-3">
                        <div class="absolute left-0 top-[6px] w-4 h-4 rounded-full bg-indigo-500 border-2 border-white shadow-md z-10"></div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mb-0.5 leading-none">Berangkat</p>
                        <div class="flex items-center gap-1.5">
                            <span class="text-lg font-bold text-gray-900 leading-none">{{ $startTime }}</span>
                        </div>
                    </div>

                    {{-- Duration Badge --}}
                    <div class="relative pl-5 my-1 z-20">
                         <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md border text-[10px] font-bold uppercase tracking-wide whitespace-nowrap bg-white
                           @if($isOvernight)
                             text-indigo-600 border-indigo-100
                           @else
                             text-gray-400 border-gray-200
                           @endif
                         ">
                           @if($isOvernight)
                            <x-heroicon-o-moon class="w-3 h-3" />
                           @else
                            <x-heroicon-o-sun class="w-3 h-3" />
                           @endif
                           {{ $duration }}
                         </div>
                    </div>

                    {{-- Return --}}
                    <div class="relative pl-5 mt-3">
                        <div class="absolute left-0 top-[6px] w-4 h-4 rounded-full bg-gray-300 border-2 border-white shadow-md z-10"></div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mb-0.5 leading-none">Pulang</p>
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                            <span class="text-base font-bold text-gray-500 leading-none">{{ $returnTimeDisplay }}</span>
                            @if($isOvernight)
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded-md flex items-center gap-1">
                                    <x-heroicon-o-calendar class="w-3 h-3" />
                                    {{ $returnDate }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Route & Purpose --}}
        <div class="flex-1 relative w-full lg:w-auto mt-2 lg:mt-0">
            {{-- Vertical Line Connector --}}
            <div class="absolute left-[7px] top-2 bottom-6 w-0.5 bg-gradient-to-b from-gray-200 to-transparent border-l border-dashed border-gray-300"></div>

            <div class="flex flex-col gap-6">
                {{-- Origin --}}
                <div class="flex items-start gap-4 relative">
                    <div class="w-4 h-4 rounded-full border-[3px] border-indigo-600 bg-white z-10 shadow-md mt-1 shrink-0"></div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Dari</p>
                        <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $record->lokasi_keberangkatan }}</h4>
                    </div>
                </div>

                {{-- Destination --}}
                <div class="flex items-start gap-4 relative">
                    <div class="w-4 h-4 rounded-full bg-indigo-600 border-2 border-indigo-200 z-10 shadow-md mt-1 shrink-0"></div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Ke</p>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $record->wilayah?->nama_wilayah ?? 'Tujuan Belum Ditentukan' }}</h4>
                            <div class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">
                                {{ $record->nama_kegiatan }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Driver & Vehicle Info --}}
        <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:w-[240px] bg-gray-50/50 rounded-2xl p-4 border border-dashed border-gray-200 w-full mt-2 lg:mt-0">
            {{-- Driver --}}
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs shadow-md shrink-0">
                    @if($driverInitials !== '-')
                        {{ $driverInitials }}
                    @else
                        <x-heroicon-o-user class="w-4 h-4" />
                    @endif
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Driver</p>
                    <p class="text-sm font-semibold text-gray-700 truncate">{{ $driverName }}</p>
                </div>
            </div>

            <div class="h-px bg-gray-200 w-full hidden lg:block"></div>

            {{-- Vehicle --}}
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                    <x-heroicon-o-truck class="w-4 h-4" />
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Kendaraan</p>
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-700">{{ $vehicleName }}</p>
                        @if($plateNumber !== '-')
                            <span class="text-[10px] font-mono bg-white border border-gray-200 px-1.5 py-0.5 rounded text-gray-500">
                                {{ $plateNumber }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Action Only (Right) --}}
        <div class="absolute top-4 right-4 lg:static lg:w-auto flex items-center justify-end">
             <x-filament::dropdown placement="bottom-end">
                <x-slot name="trigger">
                    <button class="text-gray-300 hover:text-indigo-600 hover:bg-indigo-50 p-2 rounded-xl transition-all">
                        <x-heroicon-m-ellipsis-vertical class="w-5 h-5" />
                    </button>
                </x-slot>

                <x-filament::dropdown.list>
                    <x-filament::dropdown.list.item
                        :href="\App\Filament\Resources\PerjalananResource::getUrl('view', ['record' => $record])"
                        icon="heroicon-o-eye"
                    >
                        Detail
                    </x-filament::dropdown.list.item>

                    <x-filament::dropdown.list.item
                        :href="\App\Filament\Resources\PerjalananResource::getUrl('edit', ['record' => $record])"
                        icon="heroicon-o-pencil-square"
                    >
                        Edit
                    </x-filament::dropdown.list.item>

                    {{-- Delete Action --}}
                    <x-filament::dropdown.list.item
                        color="danger"
                        wire:click="mountTableAction('delete', '{{ $record->id }}')" {{-- Corrected to mountTableAction for table actions --}}
                        icon="heroicon-o-trash"
                    >
                        Delete
                    </x-filament::dropdown.list.item>
                </x-filament::dropdown.list>
            </x-filament::dropdown>
        </div>

    </div>
</div>

