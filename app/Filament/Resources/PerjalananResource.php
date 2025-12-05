<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerjalananResource\Pages;
use App\Models\Kendaraan;
use App\Models\Perjalanan;
use App\Models\Wilayah;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class PerjalananResource extends Resource
{
    protected static ?string $model = Perjalanan::class;

    protected static ?string $navigationLabel = 'Pelayanan Perjalanan';
    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Perjalanan')
                    ->description('Detail dasar perjalanan dinas')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\ToggleButtons::make('jenis_operasional')
                                    ->label('Jenis Operasional')
                                    ->options([
                                        'Peminjaman' => 'Peminjaman',
                                        'Operasional' => 'Operasional',
                                    ])
                                    ->icons([
                                        'Peminjaman' => 'heroicon-o-arrow-right-circle',
                                        'Operasional' => 'heroicon-o-cog-6-tooth',
                                    ])
                                    ->colors([
                                        'Peminjaman' => 'success',
                                        'Operasional' => 'info',
                                    ])
                                    ->grouped()
                                    ->required()
                                    ->extraAttributes(['class' => 'justify-start']),

                                Forms\Components\ToggleButtons::make('status_perjalanan')
                                    ->label('Status Perjalanan')
                                    ->options([
                                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                                        'Terjadwal' => 'Terjadwal',
                                        'Ditolak' => 'Ditolak',
                                    ])
                                    ->icons([
                                        'Menunggu Persetujuan' => 'heroicon-o-clock',
                                        'Terjadwal' => 'heroicon-o-check-circle',
                                        'Ditolak' => 'heroicon-o-x-circle',
                                    ])
                                    ->colors([
                                        'Menunggu Persetujuan' => 'warning',
                                        'Terjadwal' => 'success',
                                        'Ditolak' => 'danger',
                                    ])
                                    ->grouped()
                                    ->required()
                                    ->extraAttributes(['class' => 'justify-center']),
                            ]),
                        Forms\Components\ToggleButtons::make('jenis_kegiatan')
                            ->label('Jenis Kegiatan')
                            ->options([
                                'LK' => 'LK',
                                'DK' => 'DK',
                                'LB' => 'LB',
                            ])
                            ->icons([
                                'LK' => 'heroicon-o-academic-cap',
                                'DK' => 'heroicon-o-building-office',
                                'LB' => 'heroicon-o-globe-alt',
                            ])
                            ->colors([
                                'LK' => 'info',
                                'DK' => 'success',
                                'LB' => 'gray',
                            ])
                            ->grouped()
                            ->required()
                            ->extraAttributes(['class' => 'justify-center']),

                    ]),

                Forms\Components\Section::make('Informasi Pengguna')
                    ->description('Data pengguna, unit kerja, dan kota kabupaten')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Select::make('unit_kerja_id')
                                    ->label('Unit Kerja/Fakultas/UKM')
                                    ->relationship('unitKerja', 'nama_unit_kerja')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama_unit_kerja')
                                            ->label('Nama Unit Kerja Baru')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->required()
                                    ->placeholder('Pilih unit kerja...'),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('nama_pengguna')
                                            ->label('Nama Pengguna')
                                            ->required()
                                            ->placeholder('Masukkan nama pengguna')
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('kontak_pengguna')
                                            ->label('Kontak Pengguna')
                                            ->placeholder('Masukkan nomor telepon/WA')
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Perjalanan')
                    ->description('Informasi lengkap perjalanan')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Forms\Components\Select::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->options([
                                'Perjalanan Dinas' => 'Perjalanan Dinas',
                                'Kuliah Lapangan' => 'Kuliah Lapangan',
                                'Kunjungan Industri' => 'Kunjungan Industri',
                                'Kegiatan Perlombaan' => 'Kegiatan Perlombaan',
                                'Kegiatan Kemahasiswaan' => 'Kegiatan Kemahasiswaan',
                                'Kegiatan Perkulihaan' => 'Kegiatan Perkulihaan',
                                'Kegiatan Lainya' => 'Kegiatan Lainya',
                            ])
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Pilih nama kegiatan...'),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('lokasi_keberangkatan')
                                    ->label('Lokasi Keberangkatan')
                                    ->required()
                                    ->placeholder('Masukkan lokasi keberangkatan')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('jumlah_rombongan')
                                    ->label('Jumlah Rombongan')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('0')
                                    ->minValue(1),
                            ]),
                        Forms\Components\Textarea::make('alamat_tujuan')
                            ->label('Alamat Tujuan')
                            ->placeholder('Masukkan alamat lengkap tujuan')
                            ->columnSpanFull()
                            ->rows(3),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('tujuan_wilayah_id')
                                    ->label('Kota Kabupaten')
                                    ->relationship('wilayah', 'nama_wilayah')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $city = Wilayah::where('wilayah_id', $state)->first();
                                        if ($city) {
                                            $set('provinsi', $city->provinsi);
                                        } else {
                                            $set('provinsi', null);
                                        }
                                    })
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama_wilayah')
                                            ->label('Nama Wilayah Baru')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('kota_kabupaten')
                                            ->label('Kota/Kabupaten')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('provinsi')
                                            ->label('Provinsi')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->required()
                                    ->placeholder('Pilih kota kabupaten...'),
                                Forms\Components\TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->disabled()
                                    ->placeholder('Provinsi akan muncul otomatis'),
                            ]),
                    ]),

                Forms\Components\Section::make('Dokumen & Berkas')
                    ->description('Upload dokumen terkait perjalanan')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('surat_peminjaman_kendaraan')
                                    ->label('Surat Peminjaman Kendaraan')
                                    ->directory('surat-peminjaman-kendaraan')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->maxSize(5120),

                                Forms\Components\FileUpload::make('dokumen_pendukung')
                                    ->label('Dokumen Pendukung')
                                    ->directory('dokumen-pendukung')
                                    ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->maxSize(5120),
                            ]),
                    ]),

                Forms\Components\Section::make('Kendaraan & Staf')
                    ->description('Informasi kendaraan dan pengemudi')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('waktu_keberangkatan')
                                    ->label('Waktu Keberangkatan')
                                    ->required()
                                    ->displayFormat('d/m/Y H:i')
                                    ->native(false),
                                Forms\Components\DateTimePicker::make('waktu_kepulangan')
                                    ->label('Waktu Kepulangan')
                                    ->displayFormat('d/m/Y H:i')
                                    ->native(false),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('pengemudi_id')
                                    ->label('Nama Pengemudi')
                                    ->relationship('pengemudi', 'nama_staf')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Pilih nama pengemudi...'),
                                Forms\Components\Select::make('asisten_id')
                                    ->label('Nama Asisten')
                                    ->relationship('asisten', 'nama_staf')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Pilih nama asisten (opsional)...'),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('nopol_kendaraan')
                                    ->label('Nomor Polisi Kendaraan')
                                    ->relationship('kendaraan', 'nopol_kendaraan')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $vehicle = Kendaraan::where('nopol_kendaraan', $state)->first();
                                        if ($vehicle) {
                                            $set('merk_type', $vehicle->merk_type);
                                            $set('foto_kendaraan', $vehicle->foto_kendaraan);
                                        } else {
                                            $set('merk_type', null);
                                            $set('foto_kendaraan', null);
                                        }
                                    })
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nopol_kendaraan')
                                            ->label('Nomor Polisi Kendaraan Baru')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('jenis_kendaraan')
                                            ->label('Jenis Kendaraan')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('merk_type')
                                            ->label('Merk/Type')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('warna_tanda')
                                            ->label('Warna/Tanda')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('tahun_pembuatan')
                                            ->label('Tahun Pembuatan')
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\TextInput::make('nomor_rangka')
                                            ->label('Nomor Rangka')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('nomor_mesin')
                                            ->label('Nomor Mesin')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('lokasi_kendaraan')
                                            ->label('Lokasi Kendaraan')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('penggunaan')
                                            ->label('Penggunaan')
                                            ->maxLength(255),
                                    ])
                                    ->required()
                                    ->placeholder('Pilih nomor polisi kendaraan...'),
                                Forms\Components\TextInput::make('merk_type')
                                    ->label('Merk & Tipe')
                                    ->disabled()
                                    ->placeholder('Merk & Tipe akan muncul otomatis'),
                                Forms\Components\ViewField::make('foto_kendaraan')
                                    ->label('Foto Kendaraan')
                                    ->view('filament.forms.components.image-preview')
                                    ->disabled(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // KARTU 1: INFO & STATUS (Middle Aligned)
                Tables\Columns\TextColumn::make('nomor_perjalanan')
                    ->label('Info & Status')
                    ->searchable()
                    ->sortable()
                    ->html()
                    ->formatStateUsing(function ($state, Perjalanan $record) {
                        // Tentukan Tema Warna Badge & Ring berdasarkan Status
                        // Menggunakan Tailwind classes
                        $badgeClasses = match ($record->status_perjalanan) {
                            'Terjadwal' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
                            'Menunggu Persetujuan' => 'bg-amber-100 text-amber-700 ring-amber-600/20',
                            'Ditolak' => 'bg-rose-100 text-rose-700 ring-rose-600/20',
                            'Selesai' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            default => 'bg-gray-100 text-gray-600 ring-gray-600/20',
                        };

                        // SVG Icons untuk Status
                        $iconSvg = match ($record->status_perjalanan) {
                            'Terjadwal', 'Selesai' => '<svg class=\"w-3 h-3 mr-1.5\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\" /></svg>',
                            'Menunggu Persetujuan' => '<svg class=\"w-3 h-3 mr-1.5\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z\" /></svg>',
                            'Ditolak' => '<svg class=\"w-3 h-3 mr-1.5\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z\" /></svg>',
                            default => '',
                        };

                        $number = $record->id; // Gunakan ID sebagai nomor urut

                        return new HtmlString('
                            <div class="flex flex-col gap-2 py-2">
                                <div>
                                   <div class="text-base font-bold text-slate-900 leading-tight">' . $state . '</div>
                                </div>
                                <div>
                                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold ring-1 ring-inset bg-white/90 shadow-sm ' . $badgeClasses . '">
                                      ' . $iconSvg . '
                                      ' . $record->status_perjalanan . '
                                  </span>
                                </div>
                            </div>
                        ');
                    }),

                // KARTU 2: JADWAL, DURASI & KOTA (Visual Timeline)
                Tables\Columns\TextColumn::make('waktu_keberangkatan')
                    ->label('Jadwal & Rute')
                    ->html()
                    ->formatStateUsing(function ($state, Perjalanan $record) {
                        $start = Carbon::parse($state);
                        $end = $record->waktu_kepulangan ? Carbon::parse($record->waktu_kepulangan) : null;

                        // Hitung Durasi Otomatis
                        if ($end) {
                            $diffInDays = $start->diffInDays($end);
                            $totalDays = $diffInDays + 1;
                            $nights = $diffInDays;
                            $duration = ($nights <= 0) ? '1 Hari' : "{$totalDays} Hari {$nights} Malam";
                            $endString = $end->format('d/m H:i');
                        } else {
                            $duration = '-';
                            $endString = '?';
                        }

                        $city = $record->wilayah?->nama_wilayah ?? '-';

                        // Render HTML Timeline Custom
                        return new HtmlString("
                            <div class='flex flex-col gap-3 py-1'>
                                <div class='flex items-start gap-2.5'>
                                    <div class='flex flex-col items-center pt-1.5'>
                                        <div class='w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-white shadow-sm'></div>
                                        <div class='w-px h-6 bg-gradient-to-b from-emerald-500 to-gray-300 my-0.5 opacity-50'></div>
                                        <div class='w-2 h-2 rounded-full bg-gray-400 ring-2 ring-white shadow-sm'></div>
                                    </div>
                                    <div class='flex flex-col'>
                                        <span class='text-xs font-bold text-gray-800 leading-none pt-0.5'>{$start->format('d/m H:i')}</span>
                                        <span class='text-[10px] text-gray-400 block pl-0.5 my-0.5'>sampai</span>
                                        <span class='text-xs font-medium text-gray-600 leading-none'>{$endString}</span>
                                    </div>
                                </div>
                                <div class='flex items-center gap-2 pl-4 border-t border-gray-100 pt-2'>
                                     <span class='inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200'>
                                        <svg class='w-3 h-3 inline mr-0.5 -mt-0.5 text-gray-400' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' /></svg>
                                        {$duration}
                                    </span>
                                    <div class='flex items-center text-[11px] font-semibold text-gray-600 truncate max-w-[120px]'>
                                        <svg class='w-3 h-3 mr-1 text-rose-500' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' /><path stroke-linecap='round' stroke-linejoin='round' d='M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z' /></svg>
                                        {$city}
                                    </div>
                                </div>
                            </div>
                        ");
                    }),

                // KARTU 3: KENDARAAN (Nopol Tebal + Merk)
                Tables\Columns\TextColumn::make('nopol_kendaraan')
                    ->label('Kendaraan')
                    ->html()
                    ->formatStateUsing(function ($state, Perjalanan $record) {
                        $type = $record->merk_type ?? '-';
                        return new HtmlString("
                            <div class='bg-white/50 p-2.5 rounded-xl border border-slate-100/50 shadow-sm'>
                                <div class='flex items-start gap-2.5'>
                                    <div class='mt-0.5 bg-slate-100 p-1.5 rounded-md text-slate-500'>
                                        <svg class='w-3.5 h-3.5' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12' /></svg>
                                    </div>
                                    <div>
                                        <div class='text-sm font-bold text-slate-900'>{$state}</div>
                                        <div class='text-[11px] font-medium text-slate-500'>{$type}</div>
                                    </div>
                                </div>
                            </div>
                        ");
                    }),

                // KARTU 4: TIM (Driver & Asisten)
                Tables\Columns\TextColumn::make('pengemudi.nama_staf')
                    ->label('Tim')
                    ->html()
                    ->formatStateUsing(function ($state, Perjalanan $record) {
                        $driverInit = substr($state ?? '?', 0, 1);
                        $asistenHtml = '';

                        if ($record->asisten) {
                            $asistenName = $record->asisten->nama_staf;
                            $asistenInit = substr($asistenName, 0, 1);
                            $asistenHtml = "
                                <div class='flex items-center gap-2.5 pl-2 border-l border-slate-300/50 ml-1'>
                                    <div class='w-5 h-5 rounded-full bg-orange-100 flex items-center justify-center text-[9px] font-bold text-orange-600 border border-white shadow-sm'>
                                        {$asistenInit}
                                    </div>
                                    <div>
                                        <span class='text-[11px] font-medium text-slate-600 block'>{$asistenName}</span>
                                        <span class='text-[9px] text-slate-400 block'>Asisten</span>
                                    </div>
                                </div>
                            ";
                        }

                        return new HtmlString("
                            <div class='flex flex-col gap-2'>
                                <div class='flex items-center gap-2.5'>
                                    <div class='w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 border border-white shadow-sm'>
                                        {$driverInit}
                                    </div>
                                    <div>
                                        <span class='text-xs font-bold text-slate-800 block'>{$state}</span>
                                        <span class='text-[10px] text-slate-400 block'>Driver</span>
                                    </div>
                                </div>
                                {$asistenHtml}
                            </div>
                        ");
                    }),

                // KARTU 5: USER & UNIT
                Tables\Columns\TextColumn::make('nama_pengguna')
                    ->label('User & Unit')
                    ->html()
                    ->formatStateUsing(function ($state, Perjalanan $record) {
                        $unit = $record->unitKerja?->nama_unit_kerja ?? '-';
                        return new HtmlString("
                            <div class='flex flex-col gap-1'>
                                <div class='flex items-center gap-2'>
                                    <svg class='w-3.5 h-3.5 text-indigo-500' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z' /></svg>
                                    <span class='text-xs font-bold text-slate-900'>{$state}</span>
                                </div>
                                <div class='flex items-start gap-1.5 text-[11px] text-slate-500 bg-white/60 p-1.5 rounded-lg border border-slate-100/50'>
                                    <svg class='w-2.5 h-2.5 mt-0.5' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5M12 6.75h1.5M15 6.75h1.5M9 9.75h1.5M12 9.75h1.5M15 9.75h1.5M9 12.75h1.5M12 12.75h1.5M15 12.75h1.5M9 15.75h1.5M12 15.75h1.5M15 15.75h1.5M9 18.75h1.5M12 18.75h1.5M15 18.75h1.5' /></svg>
                                    <span class='leading-tight'>{$unit}</span>
                                </div>
                            </div>
                        ");
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // --- STYLING BARIS (CARD LOOK - BORDER KIRI & HOVER) ---
            ->recordClasses(fn (Perjalanan $record) => match ($record->status_perjalanan) {
                'Terjadwal' => 'relative shadow-md hover:shadow-xl hover:shadow-slate-200/50 hover:-translate-y-1 border-l-4 border-l-emerald-500 bg-emerald-50/40 hover:bg-emerald-50 transition-all duration-300 ease-out rounded-xl',
                'Menunggu Persetujuan' => 'relative shadow-md hover:shadow-xl hover:shadow-slate-200/50 hover:-translate-y-1 border-l-4 border-l-amber-500 bg-amber-50/40 hover:bg-amber-50 transition-all duration-300 ease-out rounded-xl',
                'Ditolak' => 'relative shadow-md hover:shadow-xl hover:shadow-slate-200/50 hover:-translate-y-1 border-l-4 border-l-rose-500 bg-rose-50/40 hover:bg-rose-50 transition-all duration-300 ease-out rounded-xl',
                'Selesai' => 'relative shadow-md hover:shadow-xl hover:shadow-slate-200/50 hover:-translate-y-1 border-l-4 border-l-blue-500 bg-blue-50/40 hover:bg-blue-50 transition-all duration-300 ease-out rounded-xl',
                default => 'relative shadow-md hover:shadow-xl hover:shadow-slate-200/50 hover:-translate-y-1 border-l-4 border-l-slate-400 bg-slate-50/40 hover:bg-slate-50 transition-all duration-300 ease-out rounded-xl',
            })
            ->filters([
                Tables\Filters\SelectFilter::make('status_perjalanan')
                    ->options([
                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                        'Terjadwal' => 'Terjadwal',
                        'Ditolak' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_operasional')
                    ->options([
                        'Peminjaman' => 'Peminjaman',
                        'Operasional' => 'Operasional',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->color('info'),
                    Tables\Actions\EditAction::make()->color('warning'),
                    Tables\Actions\DeleteAction::make()->color('danger'),
                ])->icon('heroicon-m-ellipsis-vertical')->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->poll('30s')
            ->selectable(false)
            ->emptyStateHeading('Tidak ada data perjalanan')
            ->emptyStateIcon('heroicon-o-truck');
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
            'index' => Pages\ListPerjalanans::route('/'),
            'create' => Pages\CreatePerjalanan::route('/create'),
            'edit' => Pages\EditPerjalanan::route('/{record}/edit'),
        ];
    }
}
