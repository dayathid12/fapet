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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
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
                Tables\Columns\Layout\Grid::make(3)
                    ->schema([
                        Tables\Columns\Layout\Stack::make([
                            TextColumn::make('status_baru')
                                ->label('Status')
                                ->badge()
                                ->color(fn ($record) => $record->hasBeenProcessed() ? 'success' : 'warning')
                                ->state(fn ($record) => $record->hasBeenProcessed() ? 'Selesai' : 'Ajukan'),
                            TextColumn::make('combined_nomor')
                                ->label('Nomor Perjalanan / Surat Tugas')
                                ->state(fn ($record) => ($record->perjalanan->nomor_perjalanan ?? 'N/A') . ' / ' . ($record->perjalanan->no_surat_tugas ?? 'N/A'))
                                ->searchable(['perjalanan.nomor_perjalanan', 'perjalanan.no_surat_tugas'])
                                ->weight('bold')
                                ->color('primary'),
                        ])->space(2),
                        Tables\Columns\Layout\Stack::make([
                            TextColumn::make('pengemudi.nama_staf')
                                ->label('Pengemudi')
                                ->icon('heroicon-o-user'),
                            TextColumn::make('asisten.nama_staf')
                                ->label('Asisten')
                                ->placeholder('Tidak ada')
                                ->icon('heroicon-o-user-group'),
                        ])->space(2),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\Layout\Split::make([
                                TextColumn::make('tipe_penugasan')
                                    ->label('Tipe Penugasan')
                                    ->badge()
                                    ->color('info'),
                                TextColumn::make('perjalanan.jenis_kegiatan')
                                    ->label('Jenis Kegiatan')
                                    ->badge(),
                            ]),
                            TextColumn::make('combined_waktu')
                                ->label('Waktu Keberangkatan - Kepulangan')
                                ->state(function ($record) {
                                    $start = $record->perjalanan->waktu_keberangkatan;
                                    $end = $record->perjalanan->waktu_kepulangan;
                                    if (!$start || !$end) return 'N/A';

                                    $startCarbon = \Carbon\Carbon::parse($start);
                                    $endCarbon = \Carbon\Carbon::parse($end);

                                    $startDate = $startCarbon->format('j');
                                    $endDate = $endCarbon->format('j');
                                    $month = $startCarbon->format('F');
                                    $startTime = $startCarbon->format('H:i');
                                    $endTime = $endCarbon->format('H:i');

                                    if ($startCarbon->toDateString() === $endCarbon->toDateString()) {
                                        return $startDate . ' ' . $month . ' ' . $startTime . ' - ' . $endTime;
                                    } else {
                                        return $startDate . ' - ' . $endDate . ' ' . $month . ' ' . $startTime . ' - ' . $endTime;
                                    }
                                })
                                ->icon('heroicon-o-clock'),
                        ])->space(2),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_surat_tugas')
                    ->label('View Surat Tugas')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading('Preview Surat Tugas')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalContent(function ($record) {
                        $filePath = $record->perjalanan->upload_surat_tugas;
                        if (!$filePath) {
                            return new HtmlString('<p>Tidak ada file Scan Surat Tugas yang diunggah.</p>');
                        }

                        $fileUrl = Storage::url($filePath);
                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        $fileMimeType = Storage::mimeType($filePath);

                        if (!Storage::disk('public')->exists($filePath)) {
                            return new HtmlString('<p>File tidak ditemukan.</p>');
                        }

                        if (in_array(strtolower($fileExtension), ['pdf'])) {
                            return new HtmlString('<div style="width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden;">
                                        <iframe src="' . $fileUrl . '" style="width: 100%; height: 100%; border: none;"></iframe>
                                    </div>');
                        } elseif (Str::contains($fileMimeType, 'image')) {
                            return new HtmlString('<div style="width: 100%; text-align: center; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem;">
                                        <img src="' . $fileUrl . '" alt="Scan Surat Tugas" style="max-width: 100%; height: auto; display: block; margin: auto;">
                                    </div>');
                        } else {
                            return new HtmlString('<p>Format file tidak dapat dipratinjau langsung. <a href="' . $fileUrl . '" target="_blank" class="text-blue-500 underline">Download file</a></p>');
                        }
                    }),
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
                    Tables\Actions\BulkAction::make('print')
                        ->label('Print')
                        ->icon('heroicon-o-printer')
                        ->color('info')
                        ->modalHeading('Print Surat Tugas')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->modalContent(function (Collection $records) {
                            $content = '';
                            foreach ($records as $record) {
                                $filePath = $record->perjalanan->upload_surat_tugas;
                                if (!$filePath) {
                                    $content .= '<p>Tidak ada file Scan Surat Tugas untuk ' . ($record->perjalanan->nomor_perjalanan ?? 'N/A') . '.</p>';
                                    continue;
                                }

                                $fileUrl = Storage::url($filePath);
                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                $fileMimeType = Storage::mimeType($filePath);

                                if (!Storage::disk('public')->exists($filePath)) {
                                    $content .= '<p>File tidak ditemukan untuk ' . ($record->perjalanan->nomor_perjalanan ?? 'N/A') . '.</p>';
                                    continue;
                                }

                                if (in_array(strtolower($fileExtension), ['pdf'])) {
                                    $content .= '<div style="width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden; margin-bottom: 20px;">
                                                <iframe src="' . $fileUrl . '" style="width: 100%; height: 100%; border: none;"></iframe>
                                            </div>';
                                } elseif (Str::contains($fileMimeType, 'image')) {
                                    $content .= '<div style="width: 100%; text-align: center; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; margin-bottom: 20px;">
                                                <img src="' . $fileUrl . '" alt="Scan Surat Tugas" style="max-width: 100%; height: auto; display: block; margin: auto;">
                                            </div>';
                                } else {
                                    $content .= '<p>Format file tidak dapat dipratinjau langsung untuk ' . ($record->perjalanan->nomor_perjalanan ?? 'N/A') . '. <a href="' . $fileUrl . '" target="_blank" class="text-blue-500 underline">Download file</a></p>';
                                }
                            }
                            return new HtmlString($content);
                        })
                        ->deselectRecordsAfterCompletion(false),
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

    public static function getResourceView(string $view): string
    {
        if ($view === 'filament-panels::resources.pages.list-records') {
            return 'filament.resources.sptjb-pengemudi-resource.pages.list-sptjb-pengemudis';
        }

        return parent::getResourceView($view);
    }
}
