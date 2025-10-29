<x-filament-widgets::widget>
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">Detail Perjalanan Bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $this->getCurrentMonth())->locale('id')->isoFormat('MMMM YYYY') }}</h3>

        @if($this->getPerjalanans()->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Nama Pengguna</th>
                            <th scope="col" class="px-6 py-3">Kendaraan</th>
                            <th scope="col" class="px-6 py-3">Pengemudi</th>
                            <th scope="col" class="px-6 py-3">Asisten</th>
                            <th scope="col" class="px-6 py-3">Waktu Keberangkatan</th>
                            <th scope="col" class="px-6 py-3">Waktu Kepulangan</th>
                            <th scope="col" class="px-6 py-3">Tujuan</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->getPerjalanans() as $index => $perjalanan)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $perjalanan->nama_pengguna }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $perjalanan->kendaraan ? $perjalanan->kendaraan->merk_type . ' (' . $perjalanan->kendaraan->nopol_kendaraan . ')' : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $perjalanan->pengemudi ? $perjalanan->pengemudi->nama_staf : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $perjalanan->asisten ? $perjalanan->asisten->nama_staf : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan)->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($perjalanan->waktu_kepulangan)->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $perjalanan->tujuan }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($perjalanan->status_perjalanan == 'Terjadwal')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($perjalanan->status_perjalanan == 'Berlangsung')
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($perjalanan->status_perjalanan == 'Selesai')
                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @else
                                            bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                        @endif">
                                        {{ $perjalanan->status_perjalanan }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">Tidak ada perjalanan terjadwal untuk bulan ini.</p>
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
