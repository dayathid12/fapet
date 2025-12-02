<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerjalananResource\Pages;
use App\Filament\Resources\PerjalananResource\RelationManagers;
use App\Models\Kendaraan;
use App\Models\Perjalanan;
use App\Models\Wilayah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->collapsed()
                    ->extraAttributes(['class' => 'bg-blue-50 border border-blue-200'])
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
                                        'Permohonan' => 'Permohonan',
                                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                                        'Terjadwal' => 'Terjadwal',
                                        'Ditolak' => 'Ditolak',
                                    ])
                                    ->icons([
                                        'Permohonan' => 'heroicon-o-document-text',
                                        'Menunggu Persetujuan' => 'heroicon-o-clock',
                                        'Terjadwal' => 'heroicon-o-check-circle',
                                        'Ditolak' => 'heroicon-o-x-circle',
                                    ])
                                    ->colors([
                                        'Permohonan' => 'info',
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

                Forms\Components\Section::make('Informasi Pengguna & Detail Perjalanan')
                    ->description('Data pengguna, unit kerja, dan informasi lengkap perjalanan')
                    ->icon('heroicon-o-user-group')
                    ->collapsed()
                    ->extraAttributes(['class' => 'bg-green-50 border border-green-200'])
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
                    ->collapsed()
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

                        Forms\Components\Grid::make(3)
                            ->schema([

                            ]),
                    ]),



                Forms\Components\Section::make('Kendaraan & Staf')
                    ->description('Informasi kendaraan dan pengemudi')
                    ->icon('heroicon-o-truck')
                    ->extraAttributes(['class' => 'bg-purple-50 border border-purple-200'])
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
                                    ->getOptionLabelFromRecordUsing(fn (Kendaraan $record) => "{$record->nopol_kendaraan} ({$record->merk_type})")
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
                                        Forms\Components\TextInput::make('merk_type')
                                            ->label('Merk & Tipe')
                                            ->required()
                                            ->maxLength(255),
                                    ])
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
                Tables\Columns\TextColumn::make('nomor_perjalanan')
                    ->label('Nomor Perjalanan & Status')
                    ->formatStateUsing(function ($record) {
                        $nomor = $record->nomor_perjalanan;
                        $status = $record->status_perjalanan;
                        $statusColor = match ($status) {
                            'Permohonan' => 'info',
                            'Menunggu Persetujuan' => 'warning',
                            'Terjadwal' => 'success',
                            'Ditolak' => 'danger',
                            default => 'gray',
                        };
                        $statusIcon = match ($status) {
                            'Permohonan' => 'heroicon-o-document-text',
                            'Menunggu Persetujuan' => 'heroicon-o-arrow-path animate-spin',
                            'Terjadwal' => 'heroicon-o-check-circle',
                            'Ditolak' => 'heroicon-o-x-circle',
                            default => 'heroicon-o-question-mark-circle',
                        };
                        $capsuleClass = match ($status) {
                            'Permohonan' => 'bg-blue-100 text-blue-800 border border-blue-200',
                            'Menunggu Persetujuan' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                            'Terjadwal' => 'bg-green-100 text-green-800 border border-green-200',
                            'Ditolak' => 'bg-red-100 text-red-800 border border-red-200',
                            default => 'bg-gray-100 text-gray-800 border border-gray-200',
                        };
                        return "<div class='flex items-center space-x-2'>
                                    <div class='font-mono text-sm font-semibold'>{$nomor}</div>
                                    <div class='inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {$capsuleClass}'>
                                        <x-{$statusIcon} class='h-4 w-4 mr-1' />
                                        {$status}
                                    </div>
                                </div>";
                    })
                    ->html()
                    ->searchable(['nomor_perjalanan', 'status_perjalanan'])
                    ->sortable()
                    ->extraAttributes(['class' => 'px-4 py-3']),


                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu')
                    ->formatStateUsing(function ($record) {
                        $berangkat = $record->waktu_keberangkatan ? \Carbon\Carbon::parse($record->waktu_keberangkatan)->format('d/m/Y H:i') : '-';
                        $pulang = $record->waktu_kepulangan ? \Carbon\Carbon::parse($record->waktu_kepulangan)->format('d/m/Y H:i') : '-';
                        return "<div class='space-y-1'>
                                    <div class='flex items-center text-sm'>
                                        <x-heroicon-s-arrow-up-right class='h-4 w-4 text-green-500 mr-1' /> <span class='font-medium'>Berangkat:</span> {$berangkat}
                                    </div>
                                    <div class='flex items-center text-sm'>
                                        <x-heroicon-s-arrow-down-left class='h-4 w-4 text-red-500 mr-1' /> <span class='font-medium'>Pulang:</span> {$pulang}
                                    </div>
                                </div>";
                    })
                    ->html()
                    ->sortable(['waktu_keberangkatan'])
                    ->extraAttributes(['class' => 'px-4 py-3']),

                Tables\Columns\TextColumn::make('kendaraan')
                    ->label('Kendaraan')
                    ->formatStateUsing(function ($record) {
                        $nopol = $record->nopol_kendaraan ?: '-';
                        $merk = $record->merk_type ?: '-';
                        return "<div class='space-y-1'>
                                    <div class='flex items-center text-sm'>
                                        <x-heroicon-o-truck class='h-4 w-4 text-gray-500 mr-1' />
                                        <div class='font-mono text-sm font-bold'>{$nopol}</div>
                                    </div>
                                    <div class='text-xs text-gray-500'>{$merk}</div>
                                </div>";
                    })
                    ->html()
                    ->searchable(['nopol_kendaraan', 'merk_type'])
                    ->extraAttributes(['class' => 'px-4 py-3']),

                Tables\Columns\TextColumn::make('orang')
                    ->label('Orang')
                    ->formatStateUsing(function ($record) {
                        $driver = $record->pengemudi?->nama_staf ?: '-';
                        $user = $record->nama_pengguna ?: '-';
                        return "<div class='space-y-1'>
                                    <div class='flex items-center text-sm'>
                                        <x-heroicon-o-user class='h-4 w-4 text-blue-500 mr-1' /> <span class='font-medium'>Driver:</span> {$driver}
                                    </div>
                                    <div class='flex items-center text-sm'>
                                        <x-heroicon-o-users class='h-4 w-4 text-green-500 mr-1' /> <span class='font-medium'>Pemohon:</span> {$user}
                                    </div>
                                </div>";
                    })
                    ->html()
                    ->searchable(['pengemudi.nama_staf', 'nama_pengguna'])
                    ->extraAttributes(['class' => 'px-4 py-3']),

                Tables\Columns\TextColumn::make('unitKerja.nama_unit_kerja')
                    ->label('Unit Kerja')
                    ->icon('heroicon-o-building-office')
                    ->searchable()
                    ->extraAttributes(['class' => 'px-4 py-3']),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->extraAttributes(['class' => 'px-4 py-3']),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_perjalanan')
                    ->label('Status Perjalanan')
                    ->options([
                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                        'Terjadwal' => 'Terjadwal',
                        'Ditolak' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('jenis_operasional')
                    ->label('Jenis Operasional')
                    ->options([
                        'Peminjaman' => 'Peminjaman',
                        'Operasional' => 'Operasional',
                    ]),
                Tables\Filters\SelectFilter::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->options([
                        'Perjalanan Dinas' => 'Perjalanan Dinas',
                        'Kuliah Lapangan' => 'Kuliah Lapangan',
                        'Kunjungan Industri' => 'Kunjungan Industri',
                        'Kegiatan Perlombaan' => 'Kegiatan Perlombaan',
                        'Kegiatan Kemahasiswaan' => 'Kegiatan Kemahasiswaan',
                        'Kegiatan Perkuluntuihaan' => 'Kegiatan Perkulihaan',
                        'Kegiatan Lainya' => 'Kegiatan Lainya',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->color('info'),
                    Tables\Actions\Action::make('generatePdf')
                        ->label('PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->visible(fn (Perjalanan $record): bool => $record->status_perjalanan === 'Terjadwal')
                        ->url(fn (Perjalanan $record): string => route('perjalanan.pdf', $record->nomor_perjalanan))
                        ->openUrlInNewTab(),
                ])
                ->icon('heroicon-m-ellipsis-horizontal')
                ->color('gray'),
            ])

            ->defaultSort('updated_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->poll('30s')
            ->emptyStateHeading('Tidak ada data perjalanan')
            ->emptyStateDescription('Belum ada data pelayanan perjalanan yang tercatat.')
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
