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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class PerjalananResource extends Resource
{
    protected static ?string $model = Perjalanan::class;

    protected static ?string $navigationLabel = 'Pelayanan Perjalanan';
    protected static ?string $navigationGroup = 'Poll Kendaraan';
    protected static ?int $navigationSort = 0;

    protected static bool $hasViewAction = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\Section::make('Informasi Pengguna')
                    ->description('Data pengguna, unit kerja, dan kota kabupaten')
                    ->icon('heroicon-o-user-group')
                    ->iconColor('blue-400')
                    ->collapsible()
                    ->collapsed()
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
                                        Forms\Components\TextInput::make('nama_personil_perwakilan')
                                            ->label('Nama Perwakilan')
                                            ->placeholder('Masukkan nama perwakilan')
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('kontak_pengguna_perwakilan')
                                            ->label('Kontak Perwakilan')
                                            ->placeholder('Masukkan nomor telepon/WA perwakilan')
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Perjalanan')
                    ->description('Informasi lengkap perjalanan')
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('emerald-400')
                    ->collapsible()
                    ->collapsed()
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
                    ->iconColor('info')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('surat_peminjaman_kendaraan')
                                    ->label('Surat Peminjaman Kendaraan')
                                    ->directory('surat-peminjaman-kendaraan')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->maxSize(5120)
                                    ->downloadable()
                                    ->openable()
                                    ->hintAction(
                                        Forms\Components\Actions\Action::make('view_surat')
                                            ->label('Lihat')
                                            ->icon('heroicon-o-eye')
                                            ->color('info')
                                            ->url(fn ($record) => $record ? Storage::url($record->surat_peminjaman_kendaraan) : null, shouldOpenInNewTab: true)
                                            ->visible(fn ($record) => $record && $record->surat_peminjaman_kendaraan)
                                    ),

                                Forms\Components\FileUpload::make('dokumen_pendukung')
                                    ->label('Dokumen Pendukung')
                                    ->directory('dokumen-pendukung')
                                    ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->maxSize(5120)
                                    ->downloadable()
                                    ->openable()
                                    ->hintAction(
                                        Forms\Components\Actions\Action::make('view_dokumen')
                                            ->label('Lihat')
                                            ->icon('heroicon-o-eye')
                                            ->color('info')
                                            ->url(fn ($record) => $record ? Storage::url($record->dokumen_pendukung) : null, shouldOpenInNewTab: true)
                                            ->visible(fn ($record) => $record && $record->dokumen_pendukung)
                                    ),
                            ]),
                        Forms\Components\View::make('filament.forms.components.file-preview')
                            ->visibleOn('edit'),
                    ]),

                Forms\Components\Section::make('Kendaraan & Staf')
                    ->description('Informasi kendaraan dan pengemudi yang bertugas')
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
                                        'Selesai' => 'Selesai',
                                    ])
                                    ->icons([
                                        'Menunggu Persetujuan' => 'heroicon-o-clock',
                                        'Terjadwal' => 'heroicon-o-check-circle',
                                        'Ditolak' => 'heroicon-o-x-circle',
                                        'Selesai' => 'heroicon-o-check-badge',
                                    ])
                                    ->colors([
                                        'Menunggu Persetujuan' => 'warning',
                                        'Terjadwal' => 'success',
                                        'Ditolak' => 'danger',
                                        'Selesai' => 'primary',
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

                        \Filament\Forms\Components\Repeater::make('details')
                            ->label('Detail Kendaraan dan Staf')
                            ->relationship()
                            ->schema([
                                \Filament\Forms\Components\Select::make('kendaraan_nopol')
                                    ->label('Nomor Polisi Kendaraan')
                                    ->options(\App\Models\Kendaraan::all()->pluck('nopol_kendaraan', 'nopol_kendaraan'))
                                    ->searchable()
                                    ->required()
                                    ->placeholder('Pilih nomor polisi...'),
                                \Filament\Forms\Components\Select::make('pengemudi_id')
                                    ->label('Nama Pengemudi')
                                    ->options(\App\Models\Staf::all()->pluck('nama_staf', 'staf_id'))
                                    ->searchable()
                                    ->required()
                                    ->placeholder('Pilih pengemudi...'),
                                \Filament\Forms\Components\Select::make('asisten_id')
                                    ->label('Nama Asisten')
                                    ->options(\App\Models\Staf::all()->pluck('nama_staf', 'staf_id'))
                                    ->searchable()
                                    ->placeholder('Pilih asisten (opsional)...'),
                            ])
                            ->columns(3)
                            ->addActionLabel('Tambah Kendaraan & Staf')
                            ->minItems(1)
                            ->defaultItems(1)
                            ->cloneable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'xl' => 2,
            ])
            ->striped()
            ->recordAction('edit')
            ->columns([
                Tables\Columns\Layout\View::make('filament.tables.columns.travel-card'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_perjalanan')
                    ->options([
                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                        'Terjadwal' => 'Terjadwal',
                        'Ditolak' => 'Ditolak',
                        'Selesai' => 'Selesai',
                    ])
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('jenis_operasional')
                    ->options([
                        'Peminjaman' => 'Peminjaman',
                        'Operasional' => 'Operasional',
                    ])
                    ->label('Jenis Operasional'),

                Tables\Filters\SelectFilter::make('kendaraan_nopol')
                    ->label('Nomor Polisi Kendaraan')
                    ->options(Kendaraan::all()->pluck('nopol_kendaraan', 'nopol_kendaraan'))
                    ->searchable()
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas('details', function ($query) use ($value) {
                                $query->where('kendaraan_nopol', $value);
                            }),
                        );
                    }),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
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
            'view' => Pages\ViewPerjalanan::route('/{record}'),
            'edit' => Pages\EditPerjalanan::route('/{record}/edit'),
        ];
    }
}
