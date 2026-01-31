<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerjalananResource\Pages;
use App\Models\Staf;
use App\Models\PerjalananKendaraan;
use App\Models\Kendaraan;
use App\Models\Perjalanan;
use App\Models\Wilayah;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

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
                                    ->label('Unit Kerja')
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

                                Forms\Components\TextInput::make('unit_kerja_fakultas_ukm')
                                    ->label('Unit Kerja/Fakultas/UKM')
                                    ->placeholder('Masukkan unit kerja/fakultas/UKM')
                                    ->maxLength(255),

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

                                Forms\Components\Toggle::make('copy_user_data')
                                    ->label('Gunakan data pengguna untuk perwakilan')
                                    ->live()
                                    ->onIcon('heroicon-m-check-badge')
                                    ->offIcon('heroicon-m-x-circle')
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        if ($state) {
                                            $set('nama_personil_perwakilan', $get('nama_pengguna'));
                                            $set('kontak_pengguna_perwakilan', $get('kontak_pengguna'));
                                        }
                                    })
                                    ->columnSpanFull(),

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

                                Forms\Components\FileUpload::make('surat_izin_kegiatan')
                                    ->label('Surat Izin Kegiatan')
                                    ->directory('surat-izin-kegiatan')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->maxSize(5120)
                                    ->downloadable()
                                    ->openable()
                                    ->hintAction(
                                        Forms\Components\Actions\Action::make('view_izin')
                                            ->label('Lihat')
                                            ->icon('heroicon-o-eye')
                                            ->color('info')
                                            ->url(fn ($record) => $record ? Storage::url($record->surat_izin_kegiatan) : null, shouldOpenInNewTab: true)
                                            ->visible(fn ($record) => $record && $record->surat_izin_kegiatan)
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

                        Forms\Components\Placeholder::make('surat_peminjaman_kendaraan_preview')
                            ->label('Pratinjau Surat Peminjaman Kendaraan')
                            ->content(function (?Model $record) {
                                if (!$record || !$record->surat_peminjaman_kendaraan) {
                                    return new HtmlString('<p>Tidak ada Surat Peminjaman Kendaraan yang diunggah.</p>');
                                }

                                $filePath = $record->surat_peminjaman_kendaraan;
                                $fileUrl = Storage::url($filePath);
                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION); // Dapatkan ekstensi file

                                $fileMimeType = Storage::mimeType($filePath);

                                if (!Storage::disk('public')->exists($filePath)) {

                                    return new HtmlString('<p>File tidak ditemukan.</p>');

                                }

                                if (in_array(strtolower($fileExtension), ['pdf'])) {

                                    return new HtmlString("

                                        <div style='width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden;'>

                                            <iframe src='{$fileUrl}' style='width: 100%; height: 100%; border: none;'></iframe>

                                        </div>

                                    ");

                                } elseif (Str::contains($fileMimeType, 'image')) {

                                    return new HtmlString("

                                        <div style='width: 100%; text-align: center; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem;'>

                                            <img src='{$fileUrl}' alt='Gambar Surat Peminjaman' style='max-width: 100%; height: auto; display: block; margin: auto;'>

                                        </div>

                                    ");

                                }

                                return new HtmlString('<p>Format file tidak dapat dipratinjau langsung.</p>');
                            })
                            ->visible(fn (?Model $record) => (bool) $record),

                        Forms\Components\Placeholder::make('surat_izin_kegiatan_preview')
                            ->label('Pratinjau Surat Izin Kegiatan')
                            ->content(function (?Model $record) {
                                if (!$record || !$record->surat_izin_kegiatan) {
                                    return new HtmlString('<p>Tidak ada Surat Izin Kegiatan yang diunggah.</p>');
                                }

                                $filePath = $record->surat_izin_kegiatan;
                                $fileUrl = Storage::url($filePath);
                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION); // Dapatkan ekstensi file

                                $fileMimeType = Storage::mimeType($filePath);

                                if (!Storage::disk('public')->exists($filePath)) {
                                    return new HtmlString('<p>File tidak ditemukan.</p>');
                                }

                                if (in_array(strtolower($fileExtension), ['pdf'])) {
                                    return new HtmlString("
                                        <div style='width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden;'>
                                            <iframe src='{$fileUrl}' style='width: 100%; height: 100%; border: none;'></iframe>
                                        </div>
                                    ");
                                } elseif (Str::contains($fileMimeType, 'image')) {
                                    return new HtmlString("
                                        <div style='width: 100%; text-align: center; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem;'>
                                            <img src='{$fileUrl}' alt='Gambar Surat Izin Kegiatan' style='max-width: 100%; height: auto; display: block; margin: auto;'>
                                        </div>
                                    ");
                                }
                                return new HtmlString('<p>Format file tidak dapat dipratinjau langsung.</p>');
                            })
                            ->visible(fn (?Model $record) => (bool) $record),

                        Forms\Components\Placeholder::make('dokumen_pendukung_preview')
                            ->label('Pratinjau Dokumen Pendukung')
                            ->content(function (?Model $record) {
                                if (!$record || !$record->dokumen_pendukung) {
                                    return new HtmlString('<p>Tidak ada Dokumen Pendukung yang diunggah.</p>');
                                }

                                $filePath = $record->dokumen_pendukung;
                                $fileUrl = Storage::url($filePath);
                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION); // Dapatkan ekstensi file

                                $fileMimeType = Storage::mimeType($filePath);

                                if (!Storage::disk('public')->exists($filePath)) {

                                    return new HtmlString('<p>File tidak ditemukan.</p>');

                                }

                                if (in_array(strtolower($fileExtension), ['pdf'])) {

                                    return new HtmlString("

                                        <div style='width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden;'>

                                            <iframe src='{$fileUrl}' style='width: 100%; height: 100%; border: none;'></iframe>

                                        </div>

                                    ");

                                } elseif (Str::contains($fileMimeType, 'image')) {

                                    return new HtmlString("

                                        <div style='width: 100%; text-align: center; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem;'>

                                            <img src='{$fileUrl}' alt='Gambar Dokumen Pendukung' style='max-width: 100%; height: auto; display: block; margin: auto;'>

                                        </div>

                                    ");

                                }

                                return new HtmlString('<p>Format file tidak dapat dipratinjau langsung.</p>');
                            })
                            ->visible(fn (?Model $record) => (bool) $record),
                    ]), // Penutup Section Dokumen & Berkas

                Forms\Components\Section::make('Surat Tugas')
                    ->description('Input nomor dan upload scan surat tugas')
                    ->icon('heroicon-o-briefcase')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('no_surat_tugas')
                                    ->label('No. Surat Tugas'),

                                Forms\Components\FileUpload::make('upload_surat_tugas')
                                    ->label('Scan Surat Tugas')
                                    ->directory('scan-surat-tugas')
                                    ->downloadable()
                                    ->openable(),
                            ]),
                    ]),


                Forms\Components\Section::make('Kendaraan & Staf')
                    ->description('Informasi kendaraan dan pengemudi yang bertugas')
                    ->icon('heroicon-o-truck')
                    ->headerActions([
                        FormAction::make('surat_tugas')
                            ->label('Surat Tugas')
                            ->icon('heroicon-o-document')
                            ->color('primary')
                            ->modalHeading('Pratinjau Surat Tugas')
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                            ->modalContent(function ($record) {
                                if (!$record) {
                                    return new HtmlString('<p>Record tidak ditemukan.</p>');
                                }
                                if (!$record->upload_surat_tugas) {
                                    return new HtmlString('<p>Tidak ada file Surat Tugas yang diunggah.</p>');
                                }

                                $filePath = $record->upload_surat_tugas;
                                $fileUrl = Storage::url($filePath);
                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                $fileMimeType = Storage::mimeType($filePath);

                                if (!Storage::disk('public')->exists($filePath)) {
                                    return new HtmlString('<p>File tidak ditemukan.</p>');
                                }

                                if (in_array(strtolower($fileExtension), ['pdf'])) {
                                    return new HtmlString("
                                        <div style='width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden;'>
                                            <iframe src='{$fileUrl}' style='width: 100%; height: 100%; border: none;'></iframe>
                                        </div>
                                    ");
                                } elseif (Str::contains($fileMimeType, 'image')) {
                                    return new HtmlString("
                                        <div style='width: 100%; text-align: center; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem;'>
                                            <img src='{$fileUrl}' alt='Gambar Surat Tugas' style='max-width: 100%; height: auto; display: block; margin: auto;'>
                                        </div>
                                    ");
                                } else {
                                    return new HtmlString('<p>Format file tidak dapat dipratinjau langsung.</p>');
                                }
                            })
                            ->visible(fn ($record) => $record && $record->upload_surat_tugas && Storage::disk('public')->exists($record->upload_surat_tugas)),
                        FormAction::make('surat_perjalanan')
                            ->label('Surat Perjalanan')
                            ->icon('heroicon-o-document')
                            ->color('primary')
                            ->modalHeading('Pratinjau Surat Perjalanan')
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                            ->modalContent(function ($record) {
                                if (!$record) {
                                    return new HtmlString('<p>Record tidak ditemukan.</p>');
                                }
                                $pdfUrl = route('perjalanan.pdf', ['nomor_perjalanan' => $record->nomor_perjalanan]);
                                return new HtmlString("
                                    <div style='width: 100%; height: 600px; border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden;'>
                                        <iframe src='{$pdfUrl}' style='width: 100%; height: 100%; border: none;'></iframe>
                                    </div>
                                ");
                            })
                            ->visible(fn ($record) => $record !== null),
                        FormAction::make('edit')
                            ->label('Edit')
                            ->action(fn ($record) => redirect(PerjalananResource::getUrl('edit', ['record' => $record])))
                            ->visible(fn () => !str_contains(request()->url(), '/edit')),
                        FormAction::make('back')
                            ->label('Kembali')
                            ->icon('heroicon-o-arrow-left')
                            ->color('')
                            ->action(fn () => redirect(PerjalananResource::getUrl('index'))),
                    ])
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
                                    ->hiddenOn('view')
                                    ->extraAttributes(['class' => 'justify-start']),

                                Forms\Components\Select::make('status_perjalanan')
                                    ->label('Status Perjalanan')
                                    ->options([
                                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                                        'Terjadwal' => 'Terjadwal',
                                        'Ditolak' => 'Ditolak',
                                        'Selesai' => 'Selesai',
                                    ])
                                    ->required()
                                    ->live()
                                    ->hiddenOn('view'),
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
                            ->default(null)
                            ->hiddenOn('view')
                            ->extraAttributes(['class' => 'justify-center']),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('waktu_keberangkatan')
                                    ->label('Waktu Keberangkatan')
                                    ->required()
                                    ->displayFormat('d/m/Y H:i')
                                    ->native(false)
                                    ->live(),
                                Forms\Components\DateTimePicker::make('waktu_kepulangan')
                                    ->label('Waktu Kepulangan')
                                    ->displayFormat('d/m/Y H:i')
                                    ->native(false)
                                    ->live(),
                            ]),

                        \Filament\Forms\Components\Repeater::make('details')
                            ->label('Detail Kendaraan dan Staf')
                            ->relationship()
                            ->schema([
                                \Filament\Forms\Components\Select::make('tipe_penugasan')
                                    ->label('Tipe Tugas')
                                    ->options([
                                        'Antar & Jemput' => 'Antar & Jemput',
                                        'Antar (Keberangkatan)' => 'Antar (Keberangkatan)',
                                        'Jemput (Kepulangan)' => 'Jemput (Kepulangan)',
                                    ])

                                    ->default('Antar & Jemput')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state === 'Jemput (Kepulangan)') {
                                            $set('waktu_selesai_penugasan', $get('../../waktu_kepulangan'));
                                            if (!$get('waktu_mulai_tugas')) {
                                                $set('waktu_mulai_tugas', $get('../../waktu_kepulangan'));
                                            }
                                        } elseif ($state === 'Antar (Keberangkatan)') {
                                            $set('waktu_selesai_penugasan', $get('../../waktu_keberangkatan'));
                                        } else {
                                            $set('waktu_selesai_penugasan', null);
                                            $set('waktu_mulai_tugas', $get('../../waktu_keberangkatan'));
                                        }
                                    })
                                    ->placeholder('Pilih tipe tugas...'),
                                \Filament\Forms\Components\Select::make('kendaraan_nopol')
                                    ->label('Nomor Polisi Kendaraan')
                                    ->disabled(fn (Forms\Get $get) => !$get('tipe_penugasan'))
                                    ->options(function () {
                                        return Kendaraan::all()->mapWithKeys(function ($kendaraan) {
                                            return [$kendaraan->nopol_kendaraan => implode(' - ', array_filter([
                                                $kendaraan->nopol_kendaraan,
                                                $kendaraan->jenis_kendaraan,
                                                $kendaraan->merk_type
                                            ]))];
                                        });
                                    })
                                    ->searchable()
                                
                                    ->live()
                                    ->placeholder('Pilih nomor polisi...'),
                                \Filament\Forms\Components\DateTimePicker::make('waktu_selesai_penugasan')
                                    ->label('Waktu Selesai Penugasan')
                                    ->displayFormat('d/m/Y H:i')
                                    ->native(false)
                                    ->required(fn (Forms\Get $get): bool => $get('tipe_penugasan') === 'Antar (Keberangkatan)')
                                    ->nullable(fn (Forms\Get $get): bool => $get('tipe_penugasan') !== 'Antar (Keberangkatan)')
                                    ->visible(fn (Forms\Get $get): bool => $get('tipe_penugasan') === 'Antar (Keberangkatan)')
                                    ->live(),
                                \Filament\Forms\Components\DateTimePicker::make('waktu_mulai_tugas')
                                    ->label('Waktu Mulai Tugas')
                                    ->displayFormat('d/m/Y H:i')
                                    ->native(false)
                                    ->required(fn (Forms\Get $get): bool => !in_array($get('tipe_penugasan'), ['Antar & Jemput', 'Antar (Keberangkatan)']))
                                    ->visible(fn (Forms\Get $get): bool => !in_array($get('tipe_penugasan'), ['Antar & Jemput', 'Antar (Keberangkatan)']))
                                    ->default(fn (Forms\Get $get, $record) => $get('tipe_penugasan') === 'Jemput (Kepulangan)' && $record ? ($record->waktu_mulai_tugas ?: $get('../../waktu_kepulangan')) : null)
                                    ->live(),
                                \Filament\Forms\Components\Select::make('pengemudi_id')
                                    ->label('Nama Pengemudi')
                                    ->options(\App\Models\Staf::all()->pluck('nama_staf', 'staf_id'))
                                    ->searchable()
                                    ->placeholder('Pilih pengemudi...'),
                                \Filament\Forms\Components\Select::make('asisten_id')
                                    ->label('Nama Asisten')
                                    ->options(\App\Models\Staf::all()->pluck('nama_staf', 'staf_id'))
                                    ->searchable()
                                    ->placeholder('Pilih asisten (opsional)...'),
                            ])
                            ->columns(4)
                            ->addActionLabel('Tambah Kendaraan & Staf')
                            ->cloneable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_perjalanan')
                    ->label('Nomor Perjalanan')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('nomor_perjalanan', 'like', "%{$search}%")
                            ->orWhere('status_perjalanan', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->description(function (Perjalanan $record) {
                        $status = $record->status_perjalanan;
                        $color = match ($status) {
                            'Menunggu Persetujuan' => 'bg-yellow-500 text-white',
                            'Terjadwal' => 'text-white',
                            'Ditolak' => 'bg-red-500 text-white',
                            'Selesai' => 'bg-blue-500 text-white',
                            default => 'bg-gray-500 text-white',
                        };
                        $bgColor = $status === 'Terjadwal' ? 'style="background-color: #3BC1A8;"' : '';
                        return new HtmlString("<span class='inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {$color}' {$bgColor}>{$status}</span>");
                    }),



                Tables\Columns\TextColumn::make('waktu_keberangkatan')
                    ->label('Jadwal')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->description(fn (Perjalanan $record): string => 'Pulang: ' . ($record->waktu_kepulangan ? $record->waktu_kepulangan->format('d M Y, H:i') : 'N/A')),

                Tables\Columns\TextColumn::make('jenis_kegiatan')
                    ->label('Jenis Kegiatan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'LK' => 'info',
                        'DK' => 'success',
                        'LB' => 'gray',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_pengguna')
                    ->label('Pengguna & Unit')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('nama_pengguna', 'like', "%{$search}%")
                            ->orWhereHas('unitKerja', fn ($q) => $q->where('nama_unit_kerja', 'like', "%{$search}%"));
                    })
                    ->description(fn (Perjalanan $record): string => $record->unitKerja?->nama_unit_kerja ?? ''),

                Tables\Columns\TextColumn::make('nopol_kendaraan_search')
                    ->label('Kendaraan & Pengemudi')
                    ->getStateUsing(function (Model $record) {
                        return $record->details->pluck('kendaraan_nopol')->filter()->join(', ');
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('details', function ($q) use ($search) {
                            $q->where('kendaraan_nopol', 'like', "%{$search}%")
                              ->orWhereHas('pengemudi', fn ($q2) => $q2->where('nama_staf', 'like', "%{$search}%"));
                        });
                    })
                    ->description(function (Model $record) {
                        $drivers = $record->details->map(fn ($detail) => $detail->pengemudi?->nama_staf)->filter()->join(', ');
                        return 'Pengemudi: ' . ($drivers ?: 'N/A');
                    }),
                
                Tables\Columns\ViewColumn::make('tracking_pelacakan')->view('filament.tables.columns.tracking-pelacakan')->label('Tracking Pelacakan'),
            ])
            ->recordUrl(fn (Model $record) => PerjalananResource::getUrl('view', ['record' => $record]))
            ->recordClasses(function (Model $record) {
                return match ($record->status_perjalanan) {
                    'Menunggu Persetujuan' => 'bg-yellow-50 dark:bg-yellow-500/10',
                    'Terjadwal' => 'bg-green-50 dark:bg-green-500/10',
                    'Ditolak' => 'bg-red-50 dark:bg-red-500/10',
                    'Selesai' => 'bg-blue-50 dark:bg-blue-500/10',
                    default => null,
                };
            })

            ->selectable(false)

            ->filters([
                Tables\Filters\SelectFilter::make('status_filter')
                    ->label('Status')
                    ->options([
                        'Semua' => 'Semua',
                        'Menunggu Persetujuan' => 'Menunggu Persetujuan',
                        'Terjadwal' => 'Terjadwal',
                        'Ditolak' => 'Ditolak',
                        'Hari Ini' => 'Hari Ini',
                        'Selesai' => 'Selesai',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], function (Builder $query, $value) {
                            if ($value === 'Semua') {
                                return $query;
                            } elseif ($value === 'Hari Ini') {
                                return $query->whereDate('waktu_keberangkatan', today());
                            } else {
                                return $query->where('status_perjalanan', $value);
                            }
                        });
                    }),

                Tables\Filters\SelectFilter::make('jenis_operasional')
                    ->options([
                        'Peminjaman' => 'Peminjaman',
                        'Operasional' => 'Operasional',
                    ])
                    ->label('Jenis Operasional'),

                Tables\Filters\SelectFilter::make('kendaraan_nopol')
                    ->label('Nomor Polisi Kendaraan')
                    ->options(Kendaraan::all()->mapWithKeys(function ($kendaraan) {
                        $label = implode(' - ', array_filter([
                            $kendaraan->nopol_kendaraan,
                            $kendaraan->jenis_kendaraan,
                            $kendaraan->merk_type,
                        ]));
                        return [$kendaraan->nopol_kendaraan => $label];
                    }))
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->emptyStateHeading('Tidak ada data perjalanan')
            ->emptyStateIcon('heroicon-o-truck');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika user memiliki relasi staf (adalah pengemudi/asisten), filter berdasarkan staf_id
        if ($user && $user->staf) {
            $stafId = $user->staf->id;
            $query->whereHas('details', function (Builder $query) use ($stafId) {
                $query->where(function (Builder $subQuery) use ($stafId) {
                    $subQuery->where('pengemudi_id', $stafId)
                             ->orWhere('asisten_id', $stafId);
                });
            });
        }

        // Tambahkan filter default untuk status_perjalanan agar hanya menampilkan 'Terjadwal', 'Selesai', 'Menunggu Persetujuan', dan 'Ditolak'
        // Jika Anda ingin semua status terlihat oleh admin, tambahkan kondisi: ->when(!$user->hasRole('admin'), function ($q) { ... })
        $query->when(!$user->hasRole('admin'), function ($q) {
            $q->whereIn('status_perjalanan', ['Terjadwal', 'Selesai', 'Menunggu Persetujuan', 'Ditolak']);
        });

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
            'index' => Pages\ListPerjalanans::route('/'),
            'create' => Pages\CreatePerjalanan::route('/create'),
            'view' => Pages\ViewPerjalanan::route('/{record}'),
            'edit' => Pages\EditPerjalanan::route('/{record}/edit'),
        ];
    }
}
