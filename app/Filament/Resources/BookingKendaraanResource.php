<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingKendaraanResource\Pages;
use App\Filament\Resources\BookingKendaraanResource\RelationManagers;
use App\Models\Kendaraan;
use App\Models\Perjalanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class BookingKendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;

    protected static ?string $navigationLabel = 'Jadwal Kendaraan';
    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $currentMonth = request('month', now()->format('Y-m'));
        $startOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->endOfMonth();

        return parent::getEloquentQuery()->with(['perjalanans' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('waktu_kepulangan', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($sub) use ($startOfMonth, $endOfMonth) {
                        $sub->where('waktu_keberangkatan', '<=', $startOfMonth)
                            ->where('waktu_kepulangan', '>=', $endOfMonth);
                    });
            })->where('status_perjalanan', 'Terjadwal');
        }]);
    }

    public static function table(Table $table): Table
    {
        $currentMonth = request('month', now()->format('Y-m'));
        $startOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->endOfMonth();

        $dateColumns = [];
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $day = $date->copy(); // Use a copy for the closure
            $dateColumns[] = Tables\Columns\TextColumn::make($day->format('d'))
                ->label($day->format('j F'))
                ->html()
                ->getStateUsing(function (Kendaraan $record) use ($day) {
                    $perjalanansOnDay = $record->perjalanans->filter(function ($perjalanan) use ($day) {
                        $keberangkatan = Carbon::parse($perjalanan->waktu_keberangkatan)->startOfDay();
                        $kepulangan = Carbon::parse($perjalanan->waktu_kepulangan)->endOfDay();
                        return $day->between($keberangkatan, $kepulangan);
                    });

                    if ($perjalanansOnDay->isNotEmpty()) {
                        return $perjalanansOnDay->map(function($p) {
                            $berangkat = Carbon::parse($p->waktu_keberangkatan)->format('H:i');
                            $pulang = $p->waktu_kepulangan ? Carbon::parse($p->waktu_kepulangan)->format('H:i') : '-';
                            return "<b>{$p->nama_kegiatan}</b><br>({$berangkat} - {$pulang})";
                        })->implode('<hr class="my-1">');
                    }
                    return '';
                })
                ->color(function (Kendaraan $record) use ($day) {
                    $perjalanansOnDay = $record->perjalanans->filter(function ($perjalanan) use ($day) {
                        $keberangkatan = Carbon::parse($perjalanan->waktu_keberangkatan)->startOfDay();
                        $kepulangan = Carbon::parse($perjalanan->waktu_kepulangan)->endOfDay();
                        return $day->between($keberangkatan, $kepulangan);
                    });
                    if ($perjalanansOnDay->isEmpty()) return 'success';
                    return 'success';
                })
                ->icon(function (Kendaraan $record) use ($day) {
                    $perjalanansOnDay = $record->perjalanans->filter(function ($perjalanan) use ($day) {
                        $keberangkatan = Carbon::parse($perjalanan->waktu_keberangkatan)->startOfDay();
                        $kepulangan = Carbon::parse($perjalanan->waktu_kepulangan)->endOfDay();
                        return $day->between($keberangkatan, $kepulangan);
                    });
                    if ($perjalanansOnDay->isEmpty()) return 'heroicon-o-check';
                    return 'heroicon-o-check';
                })
                ->weight('bold')
                ->alignCenter()
                ->width('120px');
        }

        return $table
            ->columns(array_merge([
                Tables\Columns\TextColumn::make('nopol_kendaraan')
                    ->label('Nomor Polisi')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-truck')
                    ->weight('bold')
                    ->searchable()
                    ->sortable()
                    ->width('200px'),
                Tables\Columns\TextColumn::make('merk_type')
                    ->label('Merk & Tipe')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->searchable()
                    ->width('200px'),
            ], $dateColumns))
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_kendaraan')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'Minibus' => 'Minibus',
                        'SUV' => 'SUV',
                        'Mikrobus' => 'Mikrobus',
                        'Ambulance' => 'Ambulance',
                        'Pick Up' => 'Pick Up',
                    ]),
                Tables\Filters\SelectFilter::make('lokasi_kendaraan')
                    ->label('Lokasi Kendaraan')
                    ->options([
                        'Fakultas Hukum' => 'Fakultas Hukum',
                        'Fakultas Ekonomi dan Bisnis' => 'Fakultas Ekonomi dan Bisnis',
                        'Fakultas Kedokteran' => 'Fakultas Kedokteran',
                        'FMIPA' => 'FMIPA',
                        'Fakultas Pertanian' => 'Fakultas Pertanian',
                        'Fakultas Kedokteran Gigi' => 'Fakultas Kedokteran Gigi',
                        'Fakultas Ilmu Budaya' => 'Fakultas Ilmu Budaya',
                        'FISIP' => 'FISIP',
                        'Fakultas Psikologi' => 'Fakultas Psikologi',
                        'Fakultas Peternakan' => 'Fakultas Peternakan',
                        'Fakultas Ilmu Komunikasi' => 'Fakultas Ilmu Komunikasi',
                        'Fakultas Keperawatan' => 'Fakultas Keperawatan',
                        'FPIK' => 'FPIK',
                        'FTIP' => 'FTIP',
                        'Fakultas Farmasi' => 'Fakultas Farmasi',
                        'Fakultas Teknik Geologi' => 'Fakultas Teknik Geologi',
                        'Sekolah Pasca Sarjana' => 'Sekolah Pasca Sarjana',
                        'MWA' => 'MWA',
                        'Rektor' => 'Rektor',
                    ]),
                Tables\Filters\SelectFilter::make('status_booking')
                    ->label('Status Booking')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'terjadwal' => 'Terjadwal',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['value'] === 'tersedia') {
                            return $query->whereDoesntHave('perjalanans', function ($q) {
                                $currentMonth = request('month', now()->format('Y-m'));
                                $startOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
                                $endOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->endOfMonth();
                                $q->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
                                  ->where('status_perjalanan', 'Terjadwal');
                            });
                        } elseif ($data['value'] === 'terjadwal') {
                            return $query->whereHas('perjalanans', function ($q) {
                                $currentMonth = request('month', now()->format('Y-m'));
                                $startOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
                                $endOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->endOfMonth();
                                $q->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
                                  ->where('status_perjalanan', 'Terjadwal');
                            });
                        }
                        return $query;
                    }),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('select_month')
                    ->label(function () {
                        $currentMonth = request('month', now()->format('Y-m'));
                        $date = Carbon::createFromFormat('Y-m', $currentMonth);
                        return $date->locale('id')->isoFormat('MMMM YYYY');
                    })
                    ->icon('heroicon-o-calendar-days')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options(function () {
                                $options = [];
                                $currentDate = now();
                                for ($i = 0; $i < 12; $i++) {
                                    $date = $currentDate->copy()->addMonths($i);
                                    $key = $date->format('Y-m');
                                    $label = $date->locale('id')->isoFormat('MMMM YYYY');
                                    $options[$key] = $label;
                                }
                                return $options;
                            })
                            ->default(request('month', now()->format('Y-m')))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return redirect()->route('filament.app.resources.booking-kendaraans.index', ['month' => $data['month']]);
                    })
                    ->modalHeading('Pilih Bulan')
                    ->modalButton('Pilih'),
            ])
            ->defaultSort('nopol_kendaraan', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('Tidak ada data kendaraan')
            ->emptyStateDescription('Belum ada kendaraan yang terdaftar. Mulai dengan membuat kendaraan baru.')
            ->emptyStateIcon('heroicon-o-truck')
            ->poll('30s')
            ->description(function () {
                $currentMonth = request('month', now()->format('Y-m'));
                $date = Carbon::createFromFormat('Y-m', $currentMonth);
                return 'Menampilkan jadwal kendaraan untuk bulan ' . $date->locale('id')->isoFormat('MMMM YYYY') . '. Gunakan filter untuk melihat kendaraan berdasarkan status booking.';
            });
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
            'index' => Pages\ListBookingKendaraans::route('/'),
        ];
    }
}
