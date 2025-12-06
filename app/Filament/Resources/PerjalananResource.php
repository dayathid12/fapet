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
use Illuminate\Database\Eloquent\Builder;
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
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('nama_perwakilan')
                                            ->label('Nama Perwakilan')
                                            ->placeholder('Masukkan nama perwakilan')
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('kontak_perwakilan')
                                            ->label('Kontak Perwakilan')
                                            ->placeholder('Masukkan nomor telepon/WA perwakilan')
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
            ->contentGrid([
                'md' => 1,
                'xl' => 1,
            ])
            ->columns([
                Tables\Columns\ViewColumn::make('id')
                    ->label('Detail Perjalanan')
                    ->view('filament.tables.columns.perjalanan-card'),
            ])
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
            ->poll('30s')
            ->selectable(false) // Optional: jika tidak ingin ada checkbox
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
