<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SPTJBPengemudiResource\Pages;
use App\Models\PerjalananKendaraan;
use App\Models\SPTJBPengemudi;
use App\Models\SPTJBUangPengemudiDetail;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Carbon\CarbonPeriod;

class SPTJBPengemudiResource extends Resource
{
    protected static ?string $model = PerjalananKendaraan::class;

    protected static ?string $navigationLabel = 'Daftar Pengemudi';

    protected static ?string $navigationGroup = 'Pelaporan';

    protected static ?string $slug = 'daftar-sptjb-pengemudis';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('perjalanan.nomor_perjalanan')
                    ->label('Nomor Perjalanan')
                    ->searchable(),
                TextColumn::make('perjalanan.no_surat_tugas')
                    ->label('Nomor Surat Tugas')
                    ->searchable(),
                TextColumn::make('pengemudi.nama_staf')
                    ->label('Pengemudi'),
                TextColumn::make('asisten.nama_staf')
                    ->label('Asisten')
                    ->placeholder('Tidak ada'),
                TextColumn::make('kendaraan_nopol')
                    ->label('Kendaraan'),
                TextColumn::make('perjalanan.jenis_kegiatan')
                    ->label('Jenis Kegiatan'),
                TextColumn::make('tipe_penugasan')
                    ->label('Tipe Penugasan')
                    ->badge(),
                TextColumn::make('perjalanan.waktu_keberangkatan')
                    ->label('Waktu Keberangkatan')
                    ->dateTime('d M Y, H:i'),
                TextColumn::make('perjalanan.waktu_kepulangan')
                    ->label('Waktu Kepulangan')
                    ->dateTime('d M Y, H:i'),
                TextColumn::make('status_baru')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => $record->hasBeenProcessed() ? 'success' : 'warning')
                    ->state(fn ($record) => $record->hasBeenProcessed() ? 'Selesai' : 'Ajukan'),
            ])
            ->filters([
                SelectFilter::make('jenis_kegiatan')
                    ->label('Jenis Kegiatan')
                    ->multiple()
                    ->options([
                        'LK' => 'Luar Kota',
                        'LB' => 'Luar Biasa',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['values'])) {
                            return $query;
                        }
                        return $query->whereHas('perjalanan', function (Builder $q) use ($data) {
                            $q->whereIn('jenis_kegiatan', $data['values']);
                        });
                    }),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('perjalanan', function (Builder $subQuery) {
                    $subQuery->whereNotNull('upload_surat_tugas')
                             ->where('upload_surat_tugas', '!=', '');
                });
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('masukanPengemudi')
                        ->label('Masukan Pengemudi')
                        ->icon('heroicon-o-plus')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('no_sptjb')
                                ->label('No. SPTJB')
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            $sptjb = SPTJBPengemudi::firstOrCreate([
                                'no_sptjb' => $data['no_sptjb']
                            ], [
                                'uraian' => 'Uang Saku Pengemudi dalam rangka melayani Kegiatan Civitas Akademika Unpad',
                                'penerima' => 'Pengemudi dkk',
                            ]);

                            foreach ($records as $record) {
                                // Calculate tanggal_penugasan and jumlah_hari from perjalanan dates
                                $waktuKeberangkatan = $record->perjalanan->waktu_keberangkatan;
                                $waktuKepulangan = $record->perjalanan->waktu_kepulangan;
                                $tanggalPenugasan = null;
                                $jumlahHari = null;
                                if ($waktuKeberangkatan && $waktuKepulangan) {
                                    $period = CarbonPeriod::create($waktuKeberangkatan, $waktuKepulangan);
                                    $dates = [];
                                    foreach ($period as $date) {
                                        $dates[] = $date->format('j');
                                    }
                                    $tanggalPenugasan = implode(',', $dates);
                                    $jumlahHari = count($dates);
                                }

                                // For pengemudi
                                if ($record->pengemudi) {
                                    SPTJBUangPengemudiDetail::create([
                                        'sptjb_pengemudi_id' => $sptjb->id,
                                        'no' => null,
                                        'nama' => $record->pengemudi->nama_staf,
                                        'jabatan' => 'Pengemudi',
                                        'tanggal_penugasan' => $tanggalPenugasan,
                                        'jumlah_hari' => $jumlahHari,
                                        'besaran_uang_per_hari' => 150000,
                                        'jumlah_rp' => null,
                                        'jumlah_uang_diterima' => null,
                                        'nomor_rekening' => null,
                                        'golongan' => null,
                                        'no_sptjb' => $data['no_sptjb'],
                                        'nomor_perjalanan' => $record->perjalanan->nomor_perjalanan,
                                    ]);
                                }

                                // For asisten
                                if ($record->asisten) {
                                    SPTJBUangPengemudiDetail::create([
                                        'sptjb_pengemudi_id' => $sptjb->id,
                                        'no' => null,
                                        'nama' => $record->asisten->nama_staf,
                                        'jabatan' => 'Asisten',
                                        'tanggal_penugasan' => $tanggalPenugasan,
                                        'jumlah_hari' => $jumlahHari,
                                        'besaran_uang_per_hari' => 150000,
                                        'jumlah_rp' => null,
                                        'jumlah_uang_diterima' => null,
                                        'nomor_rekening' => null,
                                        'golongan' => null,
                                        'no_sptjb' => $data['no_sptjb'],
                                        'nomor_perjalanan' => $record->perjalanan->nomor_perjalanan,
                                    ]);
                                }
                            }

                            // Update no sequentially
                            $details = SPTJBUangPengemudiDetail::where('sptjb_pengemudi_id', $sptjb->id)->orderBy('id')->get();
                            foreach ($details as $index => $detail) {
                                $detail->update(['no' => $index + 1]);
                            }

                            // Notification
                            Notification::make()
                                ->title('Berhasil!')
                                ->body('Data pengemudi telah dimasukkan ke SPTJB ' . $data['no_sptjb'] . ' (ID: ' . $sptjb->id . '). Total detail: ' . $details->count())
                                ->success()
                                ->send();

                            // Redirect to edit page
                            return redirect()->to(\App\Filament\Resources\SPTJBUangPengemudiResource::getUrl('edit', ['record' => $sptjb->id]));
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSPTJBPengemudi::route('/'),
        ];
    }
}
