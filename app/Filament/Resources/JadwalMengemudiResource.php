<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalMengemudiResource\Pages;
use App\Filament\Resources\JadwalMengemudiResource\RelationManagers;
use App\Models\JadwalMengemudi;
use App\Models\Perjalanan;
use App\Models\EntryPengeluaran;
use App\Models\RincianPengeluaran;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalMengemudiResource extends Resource
{
    protected static ?string $model = Perjalanan::class;
    protected static ?string $navigationLabel = 'Jadwal Mengemudi';
    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengemudi.nama_staf') // Nama Pengemudi dari relasi Staf
                    ->label('Nama Pengemudi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_keberangkatan')
                    ->label('Waktu Berangkat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_kepulangan')
                    ->label('Waktu Kepulangan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kendaraan.nopol_kendaraan') // Nomor Polisi Kendaraan dari relasi Kendaraan
                    ->label('Nomor Polisi Kendaraan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_perjalanan') // Asumsi ada di model Perjalanan
                    ->label('Status Perjalanan')
                    ->badge() // Tampilkan sebagai badge untuk tampilan lebih baik
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'berangkat' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_personil_perwakilan') // Nama Perwakilan dari model Perjalanan
                    ->label('Nama Perwakilan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kontak_pengguna_perwakilan') // Kontak Perwakilan dari model Perjalanan
                    ->label('Kontak Perwakilan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi_keberangkatan') // Lokasi Keberangkatan dari model Perjalanan
                    ->label('Lokasi Keberangkatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_rombongan') // Jumlah Rombongan dari model Perjalanan
                    ->label('Jumlah Rombongan')
                    ->numeric(),
                Tables\Columns\TextColumn::make('alamat_tujuan') // Alamat Tujuan dari model Perjalanan
                    ->label('Alamat Tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wilayah.nama_wilayah') // Kota Kabupaten dari relasi Wilayah
                    ->label('Kota Kabupaten')
                    ->searchable()
                    ->sortable(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->check()) {
                    $user = auth()->user();
                    // Filter berdasarkan nama_lengkap dari relasi 'pengemudi' (Staf)
                    $query->whereHas('pengemudi', function (Builder $pengemudiQuery) use ($user) {
                        $pengemudiQuery->where('stafs.nama_staf', $user->name);
                    });
                }
                return $query;
            })
            ->filters([
                Tables\Filters\SelectFilter::make('status_perjalanan')
                    ->options([
                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                        'Terjadwal' => 'Terjadwal',
                        'Ditolak' => 'Ditolak',
                        'Selesai' => 'Selesai',
                    ])
                    ->label('Filter Status'),
                
                Tables\Filters\Filter::make('waktu_dan_tipe_penugasan')
                    ->form([
                        Forms\Components\Select::make('tipe_penugasan')
                            ->label('Tipe Tugas')
                            ->options([
                                'Antar & Jemput' => 'Antar & Jemput',
                                'Antar (Keberangkatan)' => 'Antar (Keberangkatan)',
                                'Jemput (Kepulangan)' => 'Jemput (Kepulangan)',
                            ])
                            ->placeholder('Semua Tipe Tugas'),
                        Forms\Components\DatePicker::make('starts_at')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('ends_at')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['starts_at'] || $data['ends_at'] || $data['tipe_penugasan'],
                            function (Builder $query) use ($data) {
                                $startDate = $data['starts_at'] ? Carbon::parse($data['starts_at'])->startOfDay() : Carbon::create(1900, 1, 1);
                                $endDate = $data['ends_at'] ? Carbon::parse($data['ends_at'])->endOfDay() : Carbon::create(2100, 12, 31);
                                $tipeTugas = $data['tipe_penugasan'] ?? null;

                                return $query->whereHas('details', function (Builder $detailsQuery) use ($startDate, $endDate, $tipeTugas) {
                                    $detailsQuery->where(function (Builder $q) use ($startDate, $endDate, $tipeTugas) {
                                        
                                        $applyAntarJemput = !$tipeTugas || $tipeTugas === 'Antar & Jemput';
                                        if ($applyAntarJemput) {
                                            $q->orWhere(function (Builder $sub) use ($startDate, $endDate) {
                                                $sub->where('tipe_penugasan', 'Antar & Jemput')
                                                    ->whereHas('perjalanan', function ($p) use ($startDate, $endDate) {
                                                        $p->where('waktu_keberangkatan', '<=', $endDate)
                                                          ->where('waktu_kepulangan', '>=', $startDate);
                                                    });
                                            });
                                        }

                                        $applyAntar = !$tipeTugas || $tipeTugas === 'Antar (Keberangkatan)';
                                        if ($applyAntar) {
                                            $q->orWhere(function (Builder $sub) use ($startDate, $endDate) {
                                                $sub->where('tipe_penugasan', 'Antar (Keberangkatan)')
                                                    ->where('waktu_selesai_penugasan', '>=', $startDate)
                                                    ->whereHas('perjalanan', function ($p) use ($endDate) {
                                                        $p->where('waktu_keberangkatan', '<=', $endDate);
                                                    });
                                            });
                                        }

                                        $applyJemput = !$tipeTugas || $tipeTugas === 'Jemput (Kepulangan)';
                                        if ($applyJemput) {
                                            $q->orWhere(function (Builder $sub) use ($startDate, $endDate) {
                                                $sub->where('tipe_penugasan', 'Jemput (Kepulangan)')
                                                    ->where('waktu_selesai_penugasan', '>=', $startDate)
                                                    ->whereHas('perjalanan', function ($p) use ($endDate) {
                                                         $p->where('waktu_kepulangan', '<=', $endDate);
                                                    });
                                            });
                                        }
                                    });
                                });
                            }
                        );
                    })
                    ->indicator('Menyaring berdasarkan waktu dan tipe penugasan'),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('surat_jalan')
                    ->label('Surat Jalan')
                    ->icon('heroicon-o-document')
                    ->url(fn ($record) => route('perjalanan.pdf', $record->nomor_perjalanan))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('input_pengeluaran')
                    ->label('Rincian Biaya')
                    ->icon('heroicon-o-banknotes') // Menggunakan ikon banknotes
                    ->url(function (Perjalanan $record): ?string {
                        // Find the associated RincianPengeluaran without creating one
                        $rincianPengeluaran = RincianPengeluaran::where('perjalanan_id', $record->id)->first();

                        if ($rincianPengeluaran) {
                            return \App\Filament\Resources\EntryPengeluaranResource::getUrl('rincian-biaya', [
                                'record' => $rincianPengeluaran->entry_pengeluaran_id,
                                'rincianPengeluaranId' => $rincianPengeluaran->id,
                            ]);
                        }
                        
                        return null;
                    })
                    ->visible(function (Perjalanan $record): bool {
                        // The button is only visible if a corresponding RincianPengeluaran exists.
                        return RincianPengeluaran::where('perjalanan_id', $record->id)->exists();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika user memiliki relasi staf, filter berdasarkan staf_id
        if ($user && $user->staf) {
            $query->whereHas('details', function (Builder $query) use ($user) {
                $query->where('pengemudi_id', $user->staf->id);
            });
        }
        // Tambahkan logika lain jika ada peran admin atau yang bisa melihat semua
        // if ($user && $user->hasRole('admin')) {
        //     return parent::getEloquentQuery(); // Admin melihat semua
        // }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalMengemudis::route('/'),
            'edit' => Pages\EditJadwalMengemudi::route('/{record}/edit'),
        ];
    }
}
