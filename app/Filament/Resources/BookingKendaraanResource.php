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
                Forms\Components\Select::make('nopol_kendaraan')
                    ->label('Nomor Polisi')
                    ->options(Kendaraan::all()->pluck('nopol_kendaraan', 'nopol_kendaraan'))
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $kendaraan = Kendaraan::where('nopol_kendaraan', $state)->first();
                        if ($kendaraan) {
                            $set('merk_type', $kendaraan->merk_type);
                        }
                    }),
                Forms\Components\TextInput::make('merk_type')
                    ->label('Merk & Tipe')
                    ->disabled()
                    ->dehydrated(false),
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
                        $content = '';
                        foreach ($perjalanansOnDay as $perjalanan) {
                            $keberangkatan = Carbon::parse($perjalanan->waktu_keberangkatan)->format('H:i');
                            $kepulangan = $perjalanan->waktu_kepulangan ? Carbon::parse($perjalanan->waktu_kepulangan)->format('H:i') : '-';
                            $content .= '<div class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-600 mb-1" style="font-family: Arial, sans-serif;">';
                            $content .= '<svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
                            $content .= htmlspecialchars($perjalanan->nama_kegiatan) . '<br>' . $keberangkatan . ' - ' . $kepulangan;
                            $content .= '</div><br>';
                        }
                        return $content;
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
                ->weight('bold')
                ->alignCenter()
                ->width('90px');
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
                    ->width('130px'),
                Tables\Columns\TextColumn::make('merk_type')
                    ->label('Merk & Tipe')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->searchable()
                    ->width('130px'),
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
            ->paginated([25, 50, 100])
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
            'create' => Pages\CreateBookingKendaraan::route('/create'),
        ];
    }
}
