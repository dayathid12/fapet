<?php

namespace App\Filament\Resources\EntryPengeluaranResource\RelationManagers;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\Perjalanan;
use App\Models\Staf;
use App\Models\UnitKerja;
use App\Filament\Resources\EntryPengeluaranResource\RelationManagers\ExportAction;
use App\Models\Kendaraan;
use App\Models\Wilayah; // Add this import
use Filament\Forms\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action; // Import Action class
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel; // Import Excel facade
use App\Exports\RincianBiayaExport; // Import your Export class

class RincianPengeluaranRelationManager extends RelationManager
{
    protected static string $relationship = 'rincianPengeluarans';

    // protected static string $view = 'filament.resources.entry-pengeluaran-resource.relation-managers.rincian-pengeluaran-relation-manager.index';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['nopol_kendaraan'])) {
            $data['nopol_kendaraan'] = explode(' - ', $data['nopol_kendaraan'])[0];
        }

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('perjalanan_id')
                    ->label('Pilih Perjalanan')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        $query = \App\Models\PerjalananKendaraan::query()
                            ->whereDoesntHave('rincianPengeluarans');

                        if (!empty($search)) {
                            $query->where(function (Builder $q) use ($search) {
                                $q->whereHas('perjalanan', function (Builder $q) use ($search) {
                                    $q->where('nama_kegiatan', 'like', "%{$search}%")
                                        ->orWhere('alamat_tujuan', 'like', "%{$search}%")
                                        ->orWhere('nomor_perjalanan', 'like', "%{$search}%");
                                })->orWhereHas('pengemudi', function (Builder $q) use ($search) {
                                    $q->where('nama_staf', 'like', "%{$search}%");
                                })->orWhereHas('kendaraan', function (Builder $q) use ($search) {
                                    $q->where('nopol_kendaraan', 'like', "%{$search}%");
                                });
                            });
                        }

                        return $query->with([
                            'perjalanan.unitKerja',
                            'perjalanan.wilayah',
                            'pengemudi',
                            'kendaraan',
                        ])->get()->mapWithKeys(function ($record) {
                            $perjalanan = $record->perjalanan;
                            if (!$perjalanan) return [];

                            $unitKerjaNama = $perjalanan->unitKerja->nama_unit_kerja ?? 'Tidak Ada Unit Kerja';
                            $wilayahNama = $perjalanan->wilayah->nama_wilayah ?? 'Tidak Ada Wilayah';
                            $waktuKeberangkatanFormatted = $perjalanan->waktu_keberangkatan ? $perjalanan->waktu_keberangkatan->format('d/m/Y') : 'Tidak Ada Waktu';
                            $driverName = $record->pengemudi ? $record->pengemudi->nama_staf : 'Tidak Ada Pengemudi';
                            $vehicleNopol = 'Tidak Ada Kendaraan';
                            if ($record->kendaraan) {
                                $vehicleNopol = implode(' - ', array_filter([
                                    $record->kendaraan->nopol_kendaraan,
                                    $record->kendaraan->jenis_kendaraan,
                                    $record->kendaraan->merk_type,
                                ]));
                            }

                            $label = $perjalanan->nomor_perjalanan .
                                ' - ' . $perjalanan->nama_kegiatan .
                                ' - ' . $unitKerjaNama .
                                ' - Pengemudi: ' . $driverName .
                                ' - Kendaraan: ' . $vehicleNopol .
                                ' - Tujuan: ' . $perjalanan->alamat_tujuan .
                                ' (' . $wilayahNama . ')' .
                                ' - Berangkat: ' . $waktuKeberangkatanFormatted;

                            return [$record->id => $label];
                        })->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $record = \App\Models\PerjalananKendaraan::with([
                            'perjalanan.unitKerja',
                            'perjalanan.wilayah',
                            'pengemudi',
                            'kendaraan',
                        ])->find($value);
                        if (!$record) return null;

                        $perjalanan = $record->perjalanan;
                        if (!$perjalanan) return null;

                        $unitKerjaNama = $perjalanan->unitKerja->nama_unit_kerja ?? 'Tidak Ada Unit Kerja';
                        $wilayahNama = $perjalanan->wilayah->nama_wilayah ?? 'Tidak Ada Wilayah';
                        $waktuKeberangkatanFormatted = $perjalanan->waktu_keberangkatan ? $perjalanan->waktu_keberangkatan->format('d/m/Y') : 'Tidak Ada Waktu';

                        $driverName = $record->pengemudi ? $record->pengemudi->nama_staf : 'Tidak Ada Pengemudi';
                        $vehicleNopol = 'Tidak Ada Kendaraan';
                        if ($record->kendaraan) {
                            $vehicleNopol = implode(' - ', array_filter([
                                $record->kendaraan->nopol_kendaraan,
                                $record->kendaraan->jenis_kendaraan,
                                $record->kendaraan->merk_type,
                            ]));
                        }

                        return $perjalanan->nomor_perjalanan .
                            ' - ' . $perjalanan->nama_kegiatan .
                            ' - ' . $unitKerjaNama .
                            ' - Pengemudi: ' . $driverName .
                            ' - Kendaraan: ' . $vehicleNopol .
                            ' - Tujuan: ' . $perjalanan->alamat_tujuan .
                            ' (' . $wilayahNama . ')' .
                            ' - Berangkat: ' . $waktuKeberangkatanFormatted;
                    })
                    ->nullable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            $perjalananKendaraan = \App\Models\PerjalananKendaraan::with([
                                'perjalanan.unitKerja',
                                'perjalanan.wilayah',
                                'pengemudi',
                                'kendaraan'
                            ])->find($state);

                            if ($perjalananKendaraan && $perjalananKendaraan->perjalanan) {
                                $perjalanan = $perjalananKendaraan->perjalanan;

                                $set('nomor_perjalanan', $perjalanan->nomor_perjalanan);
                                $set('waktu_keberangkatan', $perjalanan->waktu_keberangkatan);
                                $set('alamat_tujuan', $perjalanan->alamat_tujuan);

                                // Set driver details
                                $set('nama_pengemudi', $perjalananKendaraan->pengemudi ? $perjalananKendaraan->pengemudi->nama_staf : null);

                                // Set unit kerja details
                                $set('nama_unit_kerja', $perjalanan->unitKerja ? $perjalanan->unitKerja->nama_unit_kerja : null);

                                // Set vehicle details
                                if ($perjalananKendaraan->kendaraan) {
                                    $vehicleInfo = implode(' - ', array_filter([
                                        $perjalananKendaraan->kendaraan->nopol_kendaraan,
                                        $perjalananKendaraan->kendaraan->jenis_kendaraan,
                                        $perjalananKendaraan->kendaraan->merk_type,
                                    ]));
                                    $set('nopol_kendaraan', $vehicleInfo);
                                } else {
                                    $set('nopol_kendaraan', null);
                                }

                                // Set wilayah details
                                $set('kota_kabupaten', $perjalanan->wilayah ? $perjalanan->wilayah->nama_wilayah : null);
                            }
                        } else {
                            // Clear fields if no journey is selected
                            $set('nomor_perjalanan', null);
                            $set('waktu_keberangkatan', null);
                            $set('alamat_tujuan', null);
                            $set('nama_pengemudi', null);
                            $set('nama_unit_kerja', null);
                            $set('nopol_kendaraan', null);
                            $set('kota_kabupaten', null);
                        }
                    }),

                TextInput::make('nomor_perjalanan')
                    ->label('Nomor Perjalanan')
                    ->readOnly(),
                TextInput::make('waktu_keberangkatan')
                    ->label('Waktu Berangkat ')
                    ->readOnly()
                    ->formatStateUsing(function (?string $state): ?string {
                        \Illuminate\Support\Facades\Log::debug('Waktu Keberangkatan State: ' . $state);
                        if (empty($state)) {
                            return null;
                        }
                        try {
                            $parsedCarbon = \Carbon\Carbon::parse($state);
                            \Illuminate\Support\Facades\Log::debug('Waktu Keberangkatan Parsed Carbon: ' . $parsedCarbon->toDateTimeString());
                            return $parsedCarbon->format('d F Y');
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Failed to parse date for waktu_keberangkatan: {$state} - {$e->getMessage()}");
                            return $state; // Return original state if parsing fails
                        }
                    }),
                TextInput::make('alamat_tujuan')
                    ->label('Alamat Tujuan')
                    ->readOnly(),
                TextInput::make('nama_pengemudi')
                    ->label('Nama Pengemudi')
                    ->readOnly(),
                TextInput::make('nama_unit_kerja')
                    ->label('Unit Kerja/Fakultas/UKM')
                    ->readOnly(),
                TextInput::make('nopol_kendaraan')
                    ->label('Nomor Polisi Kendaraan')
                    ->readOnly(),

                TextInput::make('kota_kabupaten')
                    ->label('Kota Kabupaten')
                    ->readOnly(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_perjalanan')
            ->columns([
                Tables\Columns\TextColumn::make('perjalananKendaraan.perjalanan.nomor_perjalanan')
                    ->label('Nomor Perjalanan')
                    ->formatStateUsing(function ($state, $record) {
                        $url = EntryPengeluaranResource::getUrl('rincian-biaya', [
                            'record' => $this->getOwnerRecord()->id,
                            'rincianPengeluaranId' => $record->id,
                        ]);
                        $button = '<div class="flex items-center justify-center"><a href="' . $url . '" class="inline-flex items-center px-2 py-1 bg-blue-600 border border-transparent rounded font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>Tambah Biaya</a></div>';
                        return '<div class="text-center">' . $state . '</div>' . $button;
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('total_bbm')
                    ->label('Total BBM')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_toll')
                    ->label('Total Toll')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_parkir')
                    ->label('Total Parkir')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('perjalananKendaraan.pengemudi.nama_staf')
                    ->label('Nama Pengemudi'),
                Tables\Columns\TextColumn::make('perjalananKendaraan.perjalanan.waktu_keberangkatan')
                    ->label('Waktu Berangkat')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('perjalananKendaraan.perjalanan.alamat_tujuan')
                    ->label('Alamat Tujuan'),
                Tables\Columns\TextColumn::make('perjalananKendaraan.perjalanan.unitKerja.nama_unit_kerja')
                    ->label('Unit Kerja/Fakultas/UKM'),
                Tables\Columns\TextColumn::make('perjalananKendaraan.kendaraan.nopol_kendaraan')
                    ->label('Nomor Polisi Kendaraan')
                    ->formatStateUsing(function ($state, $record) {
                        if (empty($record->perjalananKendaraan->kendaraan)) {
                            return '';
                        }
                        $kendaraan = $record->perjalananKendaraan->kendaraan;
                        return implode(' - ', array_filter([
                            $kendaraan->nopol_kendaraan,
                            $kendaraan->jenis_kendaraan,
                            $kendaraan->merk_type,
                        ]));
                    }),
                Tables\Columns\TextColumn::make('perjalananKendaraan.perjalanan.wilayah.nama_wilayah')
                    ->label('Kota Kabupaten'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('back')
                    ->label('Kembali')
                    ->icon('heroicon-o-arrow-left')
                    ->color('gray')
                    ->url('/app/entry-pengeluarans'),
                Action::make('downloadExcel')
                    ->label('Download Excel')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        $includePertamaRetail = session('include_pertama_retail', true);
                        return Excel::download(new RincianBiayaExport($this->getOwnerRecord(), $includePertamaRetail), 'rincian-biaya-' . $this->getOwnerRecord()->id . '.xlsx');
                    }),
                Action::make('toggle_pertama_retail')
                    ->label(fn () => session('include_pertama_retail', true) ? 'Exclude Pertamina Retail' : 'Include Pertamina Retail')
                    ->icon(fn () => session('include_pertama_retail', true) ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn () => session('include_pertama_retail', true) ? 'success' : 'warning')
                    ->action(function () {
                        $current = session('include_pertama_retail', true);
                        session(['include_pertama_retail' => !$current]);
                    }),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('tambah_biaya')
                    ->label('Tambah Biaya')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn ($record): string => EntryPengeluaranResource::getUrl('rincian-biaya', [
                        'record' => $this->getOwnerRecord()->id,
                        'rincianPengeluaranId' => $record->id,
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        $includePertamaRetail = session('include_pertama_retail', true);
        $bbmCondition = $includePertamaRetail ? '1=1' : 'rb.pertama_retail = false';

        return $this->getRelationship()->getQuery()
            ->with([
                'rincianBiayas',
                'perjalananKendaraan.perjalanan.unitKerja',
                'perjalananKendaraan.perjalanan.wilayah',
                'perjalananKendaraan.pengemudi',
                'perjalananKendaraan.kendaraan'
            ])
            ->leftJoin('rincian_biayas as toll_biayas', function ($join) {
                $join->on('rincian_pengeluarans.id', '=', 'toll_biayas.rincian_pengeluaran_id')
                     ->where('toll_biayas.tipe', '=', 'toll');
            })
            ->leftJoin('rincian_biayas as parkir_biayas', function ($join) {
                $join->on('rincian_pengeluarans.id', '=', 'parkir_biayas.rincian_pengeluaran_id')
                     ->where('parkir_biayas.tipe', '=', 'parkir');
            })
            ->select('rincian_pengeluarans.*')
            ->selectRaw("(SELECT COALESCE(SUM(rb.biaya), 0) FROM rincian_biayas rb INNER JOIN rincian_pengeluarans rp2 ON rb.rincian_pengeluaran_id = rp2.id WHERE rp2.nomor_perjalanan = rincian_pengeluarans.nomor_perjalanan AND rb.tipe = 'bbm' AND ({$bbmCondition})) as total_bbm")
            ->selectRaw("(SELECT COALESCE(SUM(rb.biaya), 0) FROM rincian_biayas rb INNER JOIN rincian_pengeluarans rp2 ON rb.rincian_pengeluaran_id = rp2.id WHERE rp2.nomor_perjalanan = rincian_pengeluarans.nomor_perjalanan AND rb.tipe = 'toll') as total_toll")
            ->selectRaw("(SELECT COALESCE(SUM(rb.biaya), 0) FROM rincian_biayas rb INNER JOIN rincian_pengeluarans rp2 ON rb.rincian_pengeluaran_id = rp2.id WHERE rp2.nomor_perjalanan = rincian_pengeluarans.nomor_perjalanan AND rb.tipe = 'parkir') + (SELECT COALESCE(SUM(rp2.biaya_parkir), 0) FROM rincian_pengeluarans rp2 WHERE rp2.nomor_perjalanan = rincian_pengeluarans.nomor_perjalanan) as total_parkir")
            ->groupBy('rincian_pengeluarans.id');
    }

    private function extractDataFromOcrText(string $ocrText): array
    {
        Log::debug('OCR Text received: ' . $ocrText);

        $jenisBbm = null;
        $volume = null;
        $totalBiaya = null;

        // Normalize text for easier parsing (e.g., remove common typos, make case-insensitive)
        $normalizedText = strtolower($ocrText);
        $normalizedText = str_replace(['rp.', 'rp', 'idr', ','], ['', '', '', '.'], $normalizedText); // Remove currency symbols, replace comma with dot for decimals
        Log::debug('Normalized OCR Text: ' . $normalizedText);

        // --- Extract jenis_bbm ---
        $fuelKeywords = [
            'pertalite', 'pertamax turbo', 'pertamax', 'biosolar', 'dexlite', 'pertamina dex', 'solar', 'premium'
        ];
        foreach ($fuelKeywords as $keyword) {
            if (str_contains($normalizedText, $keyword)) {
                $jenisBbm = ucfirst($keyword); // Capitalize first letter
                break;
            }
        }

        // Fallback for generic fuel type if specific name not found (e.g., "BBM")
        if ($jenisBbm === null && str_contains($normalizedText, 'bbm')) {
            $jenisBbm = 'BBM Umum';
        }
        Log::debug('Extracted jenis_bbm: ' . $jenisBbm);

        // --- Extract volume ---
        // Look for patterns like "12.34 Ltr", "5.00 Liter", "Volume: 10.5"
        if (preg_match('/(\d+(\.\d+)?)\s*(ltr|liter)/i', $ocrText, $matches)) {
            $volume = (float) $matches[1];
        } elseif (preg_match('/volume\s*:\s*(\d+(\.\d+)?)/i', $ocrText, $matches)) {
            $volume = (float) $matches[1];
        }
        Log::debug('Extracted volume: ' . $volume);

        // --- Extract total_biaya ---
        // Look for patterns like "Total: 100000", "Bayar: 50000", "Rp 75.000"
        // This is tricky as "total" or "biaya" might appear multiple times.
        // Try to find the last significant monetary value.
        // Look for "total", "bayar", "jumlah" followed by a number
        if (preg_match_all('/(total|bayar|jumlah)\s*:\s*(\d{1,3}(?:\.\d{3})*(?:,\d{1,2})?|\d+)/i', $ocrText, $matches, PREG_SET_ORDER)) {
            $lastMatch = end($matches);
            $totalBiaya = (int) str_replace(['.', ','], '', $lastMatch[2]); // Remove thousands separator and comma, convert to int
        }

        // If not found, try to find "Rp " followed by a number
        if ($totalBiaya === null && preg_match_all('/rp\s*(\d{1,3}(?:\.\d{3})*(?:,\d{1,2})?|\d+)/i', $ocrText, $matches, PREG_SET_ORDER)) {
             $lastMatch = end($matches);
             $totalBiaya = (int) str_replace(['.', ','], '', $lastMatch[1]);
        }


        // Fallback: Find any large number that might represent a total, assuming it's the last one
        if ($totalBiaya === null) {
            if (preg_match_all('/\b(\d{1,3}(?:\.\d{3})*(?:,\d{1,2})?|\d+)\b/', $ocrText, $matches)) {
                $numbers = array_map(function($n){
                    return (int) str_replace(['.', ','], '', $n);
                }, $matches[0]);
                // Take the largest number found, assuming it's the total
                if (!empty($numbers)) {
                    $totalBiaya = max($numbers);
                }
            }
        }
        Log::debug('Extracted total_biaya: ' . $totalBiaya);

        return [
            'jenis_bbm' => $jenisBbm,
            'volume' => $volume,
            'total_biaya' => $totalBiaya,
        ];
    }
}

