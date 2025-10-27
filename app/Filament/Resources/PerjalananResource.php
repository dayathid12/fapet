<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerjalananResource\Pages;
use App\Filament\Resources\PerjalananResource\RelationManagers;
use App\Models\Perjalanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                    ->schema([
                        Forms\Components\Grid::make(1)
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
                                    ->required(),

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
                                    ->required(),

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
                                        'LK' => 'primary',
                                        'DK' => 'primary',
                                        'LB' => 'gray',
                                    ])
                                    ->grouped()
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make('Waktu Perjalanan')
                    ->description('Jadwal keberangkatan dan kepulangan')
                    ->icon('heroicon-o-calendar-days')
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
                                Forms\Components\Select::make('tujuan_wilayah_id')
                                    ->label('Kota Kabupaten')
                                    ->relationship('wilayah', 'nama_wilayah')
                                    ->searchable()
                                    ->preload()
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
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->placeholder('Masukkan nama kegiatan')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Dokumen & Berkas')
                    ->description('Upload dokumen terkait perjalanan')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('no_surat_tugas')
                                    ->label('No. Surat Tugas')
                                    ->required()
                                    ->placeholder('Masukkan nomor surat tugas')
                                    ->maxLength(255),
                                Forms\Components\FileUpload::make('file_surat_jalan')
                                    ->label('File Surat Jalan')
                                    ->directory('surat-jalan')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->maxSize(5120),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\FileUpload::make('docs_surat_tugas')
                                    ->label('Dokumen Surat Tugas')
                                    ->directory('surat-tugas')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->maxSize(5120),
                                Forms\Components\FileUpload::make('upload_surat_tugas')
                                    ->label('Upload Surat Tugas')
                                    ->directory('upload-surat-tugas')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->maxSize(5120),
                                Forms\Components\FileUpload::make('download_file')
                                    ->label('File Tambahan')
                                    ->directory('download-files')
                                    ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->maxSize(5120),
                            ]),
                    ]),

                Forms\Components\Section::make('Status & Verifikasi')
                    ->description('Status verifikasi perjalanan')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('status_cek_1')
                                    ->label('Status Cek 1')
                                    ->required(),
                                Forms\Components\Toggle::make('status_cek_2')
                                    ->label('Status Cek 2')
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make('Kendaraan & Staf')
                    ->description('Informasi kendaraan dan pengemudi')
                    ->icon('heroicon-o-truck')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('pengemudi_id')
                                    ->label('ID Pengemudi')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('Masukkan ID pengemudi'),
                                Forms\Components\TextInput::make('asisten_id')
                                    ->label('ID Asisten')
                                    ->numeric()
                                    ->placeholder('Masukkan ID asisten (opsional)'),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nopol_kendaraan')
                                    ->label('Nomor Polisi Kendaraan')
                                    ->required()
                                    ->placeholder('Masukkan nomor polisi kendaraan')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('tujuan_wilayah_id')
                                    ->label('ID Wilayah Tujuan')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('Masukkan ID wilayah tujuan'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_perjalanan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_keberangkatan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_kepulangan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_perjalanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_keberangkatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_rombongan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_operasional')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_operasional')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_surat_tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_surat_jalan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('docs_surat_tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('upload_surat_tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('download_file')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status_cek_1')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status_cek_2')
                    ->boolean(),
                Tables\Columns\TextColumn::make('nama_pengguna')
                    ->label('Nama Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kontak_pengguna')
                    ->label('Kontak Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pengemudi_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asisten_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nopol_kendaraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wilayah.nama_wilayah')
                    ->label('Kota Kabupaten')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unitKerja.nama_unit_kerja')
                    ->label('Unit Kerja')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
