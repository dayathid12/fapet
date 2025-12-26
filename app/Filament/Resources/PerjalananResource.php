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


                Forms\Components\Section::make('Kendaraan & Staf')
                    ->description('Informasi kendaraan dan pengemudi yang bertugas')
                    ->icon('heroicon-o-truck')
                    ->headerActions([
                        FormAction::make('edit')
                            ->label('Edit')
                            ->action(fn ($record) => redirect(PerjalananResource::getUrl('edit', ['record' => $record]))),
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
                                    ->live() // Added ->live() here
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
                                    ->required()
                                    ->default('Antar & Jemput')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state === 'Jemput (Kepulangan)') {
                                            $set('waktu_selesai_penugasan', $get('../../waktu_kepulangan'));
                                        } elseif ($state === 'Antar (Keberangkatan)') {
                                            $set('waktu_selesai_penugasan', $get('../../waktu_keberangkatan'));
                                        } else {
                                            $set('waktu_selesai_penugasan', null);
                                        }
                                    })
                                    ->placeholder('Pilih tipe tugas...'),
                                \Filament\Forms\Components\Select::make('kendaraan_nopol')
                                    ->label('Nomor Polisi Kendaraan')
                                    ->disabled(fn (Forms\Get $get) => !$get('tipe_penugasan'))
                                    ->options(function (Forms\Get $get, ?Model $record) {
                                        // --- 1. AMBIL DATA INPUT ---
                                        $globalBerangkat = $get('../../waktu_keberangkatan');
                                        $globalPulang    = $get('../../waktu_kepulangan');
                                        $tipeTugas       = $get('tipe_penugasan');
                                        $waktuSelesai    = $get('waktu_selesai_penugasan');
                                        $currentPerjalananId = $record?->id;

                                        $canFilter = false;
                                        $proposedStart = null;
                                        $proposedEnd = null;

                                        // --- 2. TENTUKAN APAKAH BISA MELAKUKAN FILTER ---
                                        // Cek apakah semua data yang diperlukan untuk filtering sudah ada berdasarkan Tipe Tugas.
                                        if ($tipeTugas === 'Antar & Jemput' && $globalBerangkat && $globalPulang) {
                                            $canFilter = true;
                                            $proposedStart = $globalBerangkat;
                                            $proposedEnd   = $globalPulang;
                                        } elseif ($tipeTugas === 'Antar (Keberangkatan)' && $globalBerangkat && $waktuSelesai) {
                                            $canFilter = true;
                                            $proposedStart = $globalBerangkat;
                                            $proposedEnd   = $waktuSelesai;
                                        } elseif ($tipeTugas === 'Jemput (Kepulangan)' && $globalPulang && $waktuSelesai) {
                                            $canFilter = true;
                                            $proposedStart = $globalPulang;
                                            $proposedEnd   = $waktuSelesai;
                                        }

                                        // --- 3. JIKA DATA BELUM LENGKAP, TAMPILKAN SEMUA KENDARAAN ---
                                        if (!$canFilter) {
                                            return Kendaraan::all()->mapWithKeys(function ($kendaraan) {
                                                return [$kendaraan->nopol_kendaraan => implode(' - ', array_filter([
                                                    $kendaraan->nopol_kendaraan,
                                                    $kendaraan->jenis_kendaraan,
                                                    $kendaraan->merk_type
                                                ]))];
                                            });
                                        }

                                        // --- 4. VALIDASI RENTANG WAKTU ---
                                        // Jika data sudah lengkap tapi rentangnya tidak valid (misal: waktu selesai < waktu mulai)
                                        if ($proposedEnd <= $proposedStart) {
                                            return []; // Kosongkan pilihan untuk menandakan input waktu salah.
                                        }

                                        // --- 5. JALANKAN QUERY FILTERING ---
                                        // Jika data lengkap dan valid, filter kendaraan yang bentrok jadwalnya.
                                        return Kendaraan::whereDoesntHave('perjalananKendaraans', function (Builder $query) use ($proposedStart, $proposedEnd, $currentPerjalananId) {
                                            $query
                                                // Hanya periksa perjalanan yang berstatus 'Terjadwal'
                                                ->whereHas('perjalanan', function (Builder $perjalananQuery) use ($currentPerjalananId) {
                                                    $perjalananQuery->where('status_perjalanan', 'Terjadwal')
                                                        // Abaikan perjalanan saat ini (untuk mode edit)
                                                        ->when($currentPerjalananId, function ($q) use ($currentPerjalananId) {
                                                            $q->where('id', '!=', $currentPerjalananId);
                                                        });
                                                })
                                                // Terapkan logika overlap
                                                ->where(function (Builder $overlapQuery) use ($proposedStart, $proposedEnd) {
                                                    // Kondisi 1: Bentrok dengan jadwal 'Antar & Jemput' yang ada
                                                    $overlapQuery->orWhere(function (Builder $subQuery) use ($proposedStart, $proposedEnd) {
                                                        $subQuery->where('tipe_penugasan', 'Antar & Jemput')
                                                                ->whereHas('perjalanan', function ($p) use ($proposedStart, $proposedEnd) {
                                                                    $p->where('waktu_keberangkatan', '<', $proposedEnd)
                                                                    ->where('waktu_kepulangan', '>', $proposedStart);
                                                                });
                                                    });

                                                    // Kondisi 2: Bentrok dengan jadwal 'Antar (Keberangkatan)' yang ada
                                                    $overlapQuery->orWhere(function (Builder $subQuery) use ($proposedStart, $proposedEnd) {
                                                        $subQuery->where('tipe_penugasan', 'Antar (Keberangkatan)')
                                                                ->where('waktu_selesai_penugasan', '>', $proposedStart)
                                                                ->whereHas('perjalanan', function ($p) use ($proposedEnd) {
                                                                    $p->where('waktu_keberangkatan', '<', $proposedEnd);
                                                                });
                                                    });
                                                    
                                                    // Kondisi 3: Bentrok dengan jadwal 'Jemput (Kepulangan)' yang ada
                                                    $overlapQuery->orWhere(function (Builder $subQuery) use ($proposedStart, $proposedEnd) {
                                                        $subQuery->where('tipe_penugasan', 'Jemput (Kepulangan)')
                                                                ->where('waktu_selesai_penugasan', '>', $proposedStart)
                                                                ->whereHas('perjalanan', function ($p) use ($proposedEnd) {
                                                                    $p->where('waktu_kepulangan', '<', $proposedEnd);
                                                                });
                                                    });
                                                });
                                        })
                                        ->get()
                                        ->mapWithKeys(function ($kendaraan) {
                                            return [$kendaraan->nopol_kendaraan => implode(' - ', array_filter([
                                                $kendaraan->nopol_kendaraan,
                                                $kendaraan->jenis_kendaraan,
                                                $kendaraan->merk_type,
                                            ]))];
                                        });
                                    })
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->placeholder('Pilih nomor polisi...'),
                                \Filament\Forms\Components\DateTimePicker::make('waktu_selesai_penugasan')
                                    ->label('Waktu Selesai Penugasan')
                                    ->displayFormat('d/m/Y H:i')
                                    ->native(false)
                                    ->required(fn (Forms\Get $get): bool => in_array($get('tipe_penugasan'), ['Antar (Keberangkatan)', 'Jemput (Kepulangan)']))
                                    ->nullable(fn (Forms\Get $get): bool => !in_array($get('tipe_penugasan'), ['Antar (Keberangkatan)', 'Jemput (Kepulangan)']))
                                    ->visible(fn (Forms\Get $get): bool => in_array($get('tipe_penugasan'), ['Antar (Keberangkatan)', 'Jemput (Kepulangan)']))
                                    ->live(),
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
                            ->columns(4)
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
