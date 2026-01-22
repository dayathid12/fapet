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
                    ->label('Nomor Perjalanan'),
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
                        return Excel::download(new RincianBiayaExport($this->getOwnerRecord()), 'rincian-biaya-' . $this->getOwnerRecord()->id . '.xlsx');
                    }),
                Action::make('toggle_pertama_retail')
                    ->label(fn () => session('include_pertama_retail', true) ? 'Exclude Pertama Retail' : 'Include Pertama Retail')
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
        $bbmCondition = $includePertamaRetail ? '1=1' : 'bbm_biayas.pertama_retail = false';

        return $this->getRelationship()->getQuery()
            ->with([
                'rincianBiayas',
                'perjalananKendaraan.perjalanan.unitKerja',
                'perjalananKendaraan.perjalanan.wilayah',
                'perjalananKendaraan.pengemudi',
                'perjalananKendaraan.kendaraan'
            ])
            ->leftJoin('rincian_biayas as bbm_biayas', function ($join) {
                $join->on('rincian_pengeluarans.id', '=', 'bbm_biayas.rincian_pengeluaran_id')
                     ->where('bbm_biayas.tipe', '=', 'bbm');
            })
            ->leftJoin('rincian_biayas as toll_biayas', function ($join) {
                $join->on('rincian_pengeluarans.id', '=', 'toll_biayas.rincian_pengeluaran_id')
                     ->where('toll_biayas.tipe', '=', 'toll');
            })
            ->leftJoin('rincian_biayas as parkir_biayas', function ($join) {
                $join->on('rincian_pengeluarans.id', '=', 'parkir_biayas.rincian_pengeluaran_id')
                     ->where('parkir_biayas.tipe', '=', 'parkir');
            })
            ->select('rincian_pengeluarans.*')
            ->selectRaw("COALESCE(SUM(CASE WHEN {$bbmCondition} THEN bbm_biayas.biaya ELSE 0 END), 0) as total_bbm")
            ->selectRaw('COALESCE(SUM(toll_biayas.biaya), 0) as total_toll')
            ->selectRaw('COALESCE(SUM(parkir_biayas.biaya), 0) + COALESCE(rincian_pengeluarans.biaya_parkir, 0) as total_parkir')
            ->groupBy('rincian_pengeluarans.id');
    }
}
