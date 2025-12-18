<?php

namespace App\Filament\Resources\EntryPengeluaranResource\RelationManagers;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\Perjalanan;
use App\Models\Staf;
use App\Models\UnitKerja;
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
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RincianPengeluaranRelationManager extends RelationManager
{
    protected static string $relationship = 'rincianPengeluarans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('perjalanan_id')
                    ->label('Pilih Perjalanan')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => $this->getPerjalananOptions($search))
                    ->getOptionLabelUsing(function (string $value): ?string {
                        $perjalananKendaraan = \App\Models\PerjalananKendaraan::with([
                            'perjalanan.unitKerja',
                            'perjalanan.wilayah',
                            'pengemudi',
                            'kendaraan'
                        ])->find($value);

                        if (!$perjalananKendaraan || !$perjalananKendaraan->perjalanan) {
                            return null;
                        }

                        $perjalanan = $perjalananKendaraan->perjalanan;
                        $unitKerjaNama = $perjalanan->unitKerja->nama_unit_kerja ?? 'Tidak Ada Unit Kerja';
                        $wilayahNama = $perjalanan->wilayah->nama_wilayah ?? 'Tidak Ada Wilayah';
                        $waktuKeberangkatanFormatted = $perjalanan->waktu_keberangkatan ? $perjalanan->waktu_keberangkatan->format('d/m/Y') : 'Tidak Ada Waktu';

                        $driverName = $perjalananKendaraan->pengemudi ? $perjalananKendaraan->pengemudi->nama_staf : 'Tidak Ada Pengemudi';
                        $vehicleNopol = $perjalananKendaraan->kendaraan ? $perjalananKendaraan->kendaraan->nopol_kendaraan : 'Tidak Ada Kendaraan';

                        return $perjalanan->nomor_perjalanan .
                            ' - ' . $perjalanan->nama_kegiatan .
                            ' - ' . $unitKerjaNama .
                            ' - Pengemudi: ' . $driverName .
                            ' - Kendaraan: ' . $vehicleNopol .
                            ' - Tujuan: ' . $perjalanan->alamat_tujuan .
                            ' (' . $wilayahNama . ')' .
                            ' - Berangkat: ' . $waktuKeberangkatanFormatted;
                    })
                    ->searchable()
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
                                $set('nopol_kendaraan', $perjalananKendaraan->kendaraan ? $perjalananKendaraan->kendaraan->nopol_kendaraan : null);

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
                    ->disabled(),
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
                    ->disabled(),
                TextInput::make('nama_pengemudi')
                    ->label('Nama Pengemudi')
                    ->disabled(),
                TextInput::make('nama_unit_kerja')
                    ->label('Unit Kerja/Fakultas/UKM')
                    ->disabled(),
                TextInput::make('nopol_kendaraan')
                    ->label('Nomor Polisi Kendaraan')
                    ->disabled(),

                TextInput::make('kota_kabupaten')
                    ->label('Kota Kabupaten')
                    ->disabled(),
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
                    ->label('Nomor Polisi Kendaraan'),
                Tables\Columns\TextColumn::make('perjalananKendaraan.perjalanan.wilayah.nama_wilayah')
                    ->label('Kota Kabupaten'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
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
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getPerjalananOptions(?string $search = null): array
    {
        $options = [];
        $query = \App\Models\PerjalananKendaraan::with([
            'perjalanan' => function ($query) {
                $query->with(['unitKerja', 'wilayah']);
            },
            'pengemudi',
            'kendaraan'
        ])
        ->whereHas('perjalanan', function ($query) {
            $query->whereIn('status_perjalanan', ['terjadwal', 'selesai']);
        });

        if ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->whereHas('perjalanan', function ($perjalananQuery) use ($search) {
                    $perjalananQuery->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('alamat_tujuan', 'like', "%{$search}%")
                        ->orWhere('nomor_perjalanan', 'like', "%{$search}%");
                    // Search in unitKerja relation
                    $perjalananQuery->orWhereHas('unitKerja', function ($unitKerjaQuery) use ($search) {
                        $unitKerjaQuery->where('nama_unit_kerja', 'like', "%{$search}%");
                    });
                    // Search in wilayah relation
                    $perjalananQuery->orWhereHas('wilayah', function ($wilayahQuery) use ($search) {
                        $wilayahQuery->where('nama_wilayah', 'like', "%{$search}%");
                    });
                })
                ->orWhereHas('pengemudi', function ($pengemudiQuery) use ($search) {
                    $pengemudiQuery->where('nama_staf', 'like', "%{$search}%");
                })
                ->orWhereHas('kendaraan', function ($kendaraanQuery) use ($search) {
                    $kendaraanQuery->where('nopol_kendaraan', 'like', "%{$search}%");
                });
            });
        }

        $perjalananKendaraans = $query->get();

        foreach ($perjalananKendaraans as $pk) {
            if (!$pk->perjalanan) {
                continue;
            }

            $perjalanan = $pk->perjalanan;
            $unitKerjaNama = $perjalanan->unitKerja->nama_unit_kerja ?? 'Tidak Ada Unit Kerja';
            $wilayahNama = $perjalanan->wilayah->nama_wilayah ?? 'Tidak Ada Wilayah';
            $waktuKeberangkatanFormatted = $pk->perjalanan->waktu_keberangkatan ? $pk->perjalanan->waktu_keberangkatan->format('d/m/Y') : 'Tidak Ada Waktu';

            $driverName = $pk->pengemudi ? $pk->pengemudi->nama_staf : 'Tidak Ada Pengemudi';
            $vehicleNopol = $pk->kendaraan ? $pk->kendaraan->nopol_kendaraan : 'Tidak Ada Kendaraan';

            $optionKey = $pk->id;

            $optionLabel = $perjalanan->nomor_perjalanan .
                ' - ' . $perjalanan->nama_kegiatan .
                ' - ' . $unitKerjaNama .
                ' - Pengemudi: ' . $driverName .
                ' - Kendaraan: ' . $vehicleNopol .
                ' - Tujuan: ' . $perjalanan->alamat_tujuan .
                ' (' . $wilayahNama . ')' .
                ' - Berangkat: ' . $waktuKeberangkatanFormatted;

            $options[$optionKey] = $optionLabel;
        }
        return $options;
    }
}
