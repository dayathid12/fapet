<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use App\Models\Wilayah;
use App\Models\Perjalanan;
use App\Models\UnitKerja;
use Livewire\Attributes\Reactive;
use Closure;

class PeminjamanKendaraanUnpad extends Page implements \Filament\Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.peminjaman-kendaraan-unpad';
    protected static ?string $title = 'Peminjaman Kendaraan Unpad';
    protected static bool $shouldSkipContentWrapper = true;

    #[Reactive]
    public $currentStep = 1;

    public ?array $data = [];

    public $showSuccessModal = false;
    public $trackingUrl = '';

    private $wilayahOptions = null;
    private $unitKerjaOptions = null;

    public function mount(): void
    {
        $this->data = [];
        $this->form->fill($this->data);
    }

    public function getWilayahOptions()
    {
        if ($this->wilayahOptions === null) {
            $this->wilayahOptions = Wilayah::pluck('nama_wilayah', 'wilayah_id')->toArray();
        }
        return $this->wilayahOptions;
    }

    public function getUnitKerjaOptions()
    {
        if ($this->unitKerjaOptions === null) {
            $this->unitKerjaOptions = UnitKerja::pluck('nama_unit_kerja', 'unit_kerja_id')->toArray();
        }
        return $this->unitKerjaOptions;
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // ===== STEP 1: INFORMASI PERJALANAN =====
                Grid::make(2)->schema([
                    DateTimePicker::make('waktu_keberangkatan')
                        ->label('Waktu Keberangkatan')
                        ->required()
                        ->native(false),
                    DateTimePicker::make('waktu_kepulangan')
                        ->label('Waktu Kepulangan')
                        ->native(false),
                ])->visible(fn () => $this->currentStep === 1),

                Grid::make(2)->schema([
                    TextInput::make('lokasi_keberangkatan')
                        ->label('Lokasi Keberangkatan')
                        ->required(),
                    TextInput::make('jumlah_rombongan')
                        ->label('Jumlah Rombongan')
                        ->required()
                        ->numeric()
                        ->minValue(1),
                ])->visible(fn () => $this->currentStep === 1),

                Textarea::make('alamat_tujuan')
                    ->label('Alamat Tujuan')
                    ->required()
                    ->rows(3)
                    ->visible(fn () => $this->currentStep === 1),

                Grid::make(2)->schema([
                    Select::make('nama_kegiatan')
                        ->label('Nama Kegiatan')
                        ->options([
                            'Perjalanan Dinas' => 'Perjalanan Dinas',
                            'Kuliah Lapangan' => 'Kuliah Lapangan',
                            'Kunjungan Industri' => 'Kunjungan Industri',
                            'Kegiatan Perlombaan' => 'Kegiatan Perlombaan',
                            'Kegiatan Kemahasiswaan' => 'Kegiatan Kemahasiswaan',
                            'Kegiatan Perkuliahan' => 'Kegiatan Perkuliahan',
                            'Kegiatan Lainnya' => 'Kegiatan Lainnya',
                        ])
                        ->searchable()
                        ->required(),

                    Select::make('tujuan_wilayah_id')
                        ->label('Kota Kabupaten')
                        ->options(fn () => $this->getWilayahOptions())
                        ->searchable()
                        ->required(),
                ])->visible(fn () => $this->currentStep === 1),

                // ===== STEP 2: INFORMASI PENGGUNA =====
                Select::make('unit_kerja_id')
                    ->label('Unit Kerja/Fakultas/UKM')
                    ->options(fn () => $this->getUnitKerjaOptions())
                    ->searchable()
                    ->required()
                    ->visible(fn () => $this->currentStep === 2),

                TextInput::make('nama_pengguna')
                    ->label('Nama Pengguna')
                    ->required()
                    ->visible(fn () => $this->currentStep === 2),

                TextInput::make('kontak_pengguna')
                    ->label('Kontak Pengguna (HP/WA)')
                    ->required()
                    ->tel()
                    ->visible(fn () => $this->currentStep === 2),

                Checkbox::make('use_same_info')
                    ->label('Gunakan informasi yang sama untuk Personil Perwakilan')
                    ->reactive()
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                        if ($state) {
                            $set('nama_personil_perwakilan', $get('nama_pengguna'));
                            $set('kontak_pengguna_perwakilan', $get('kontak_pengguna'));
                        } else {
                            $set('nama_personil_perwakilan', null);
                            $set('kontak_pengguna_perwakilan', null);
                        }
                    })
                    ->visible(fn () => $this->currentStep === 2),

                TextInput::make('nama_personil_perwakilan')
                    ->label('Nama Personil Perwakilan')
                    ->required()
                    ->visible(fn () => $this->currentStep === 2),

                TextInput::make('kontak_pengguna_perwakilan')
                    ->label('Kontak Personil Perwakilan (HP/WA)')
                    ->required()
                    ->tel()
                    ->visible(fn () => $this->currentStep === 2),

                Select::make('status_sebagai')
                    ->label('Status Sebagai')
                    ->options([
                        'Mahasiswa' => 'Mahasiswa',
                        'Dosen' => 'Dosen',
                        'Staf' => 'Staf',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->searchable()
                    ->required()
                    ->visible(fn () => $this->currentStep === 2),

                // ===== STEP 3: DETAIL PERJALANAN =====
                Select::make('tujuan_wilayah_id_step3')
                    ->label('Kota Kabupaten')
                    ->options(fn () => $this->getWilayahOptions())
                    ->searchable()
                    ->required()
                    ->placeholder('Pilih kota kabupaten...')
                    ->visible(fn () => $this->currentStep === 3),

                TextInput::make('provinsi')
                    ->label('Provinsi')
                    ->disabled()
                    ->visible(fn () => $this->currentStep === 3),

                Textarea::make('uraian_singkat_kegiatan')
                    ->label('Uraian Singkat Kegiatan')
                    ->rows(3)
                    ->required()
                    ->visible(fn () => $this->currentStep === 3),

                Textarea::make('catatan_keterangan_tambahan')
                    ->label('Catatan/Keterangan Tambahan')
                    ->rows(3)
                    ->visible(fn () => $this->currentStep === 3),

                \Filament\Forms\Components\FileUpload::make('surat_peminjaman_kendaraan')
                    ->label('Surat Peminjaman Kendaraan')
                    ->directory('surat-peminjaman-kendaraan')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(5120)
                    ->required()
                    ->visible(fn () => $this->currentStep === 3),

                \Filament\Forms\Components\FileUpload::make('surat_izin_kegiatan')
                    ->label('Surat Izin Kegiatan')
                    ->directory('surat-izin-kegiatan')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(5120)
                    ->visible(fn () => $this->currentStep === 3),

                \Filament\Forms\Components\FileUpload::make('dokumen_pendukung')
                    ->label('Dokumen Pendukung')
                    ->directory('dokumen-pendukung')
                    ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(5120)
                    ->visible(fn () => $this->currentStep === 3),
            ])
            ->statePath('data');
    }

    /**
     * Check if current step has all required fields filled
     */
    public function isCurrentStepValid(): bool
    {
        $data = $this->data ?? [];

        return match ($this->currentStep) {
            1 => !empty($data['waktu_keberangkatan'] ?? null)
                && !empty($data['lokasi_keberangkatan'] ?? null)
                && !empty($data['jumlah_rombongan'] ?? null)
                && !empty($data['alamat_tujuan'] ?? null)
                && !empty($data['nama_kegiatan'] ?? null)
                && !empty($data['tujuan_wilayah_id'] ?? null),
            2 => !empty($data['unit_kerja_id'] ?? null)
                && !empty($data['nama_pengguna'] ?? null)
                && !empty($data['kontak_pengguna'] ?? null)
                && !empty($data['nama_personil_perwakilan'] ?? null)
                && !empty($data['kontak_pengguna_perwakilan'] ?? null)
                && !empty($data['status_sebagai'] ?? null),
            3 => !empty($data['tujuan_wilayah_id_step3'] ?? null) && !empty($data['surat_peminjaman_kendaraan'] ?? null),
            4 => true,
            default => false,
        };
    }

    public function nextStep(): void
    {
        if ($this->currentStep < 4 && $this->isCurrentStepValid()) {
            $this->currentStep++;
        } else {
            Notification::make()
                ->title('Peringatan')
                ->body('Mohon lengkapi semua field yang diperlukan terlebih dahulu.')
                ->warning()
                ->send();
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit(): void
    {
        $data = $this->data;

        if (!empty($data['tujuan_wilayah_id_step3'])) {
            $data['tujuan_wilayah_id'] = $data['tujuan_wilayah_id_step3'];
            // Fetch provinsi based on tujuan_wilayah_id
            $wilayah = Wilayah::find($data['tujuan_wilayah_id']);
            if ($wilayah) {
                $data['provinsi'] = $wilayah->provinsi;
            }
        }
        unset($data['tujuan_wilayah_id_step3']);

        $data['jenis_kegiatan'] = $data['nama_kegiatan'] ?? null;
        // nopol_kendaraan is now nullable, so setting to null is acceptable if not provided
        // We can remove this line if we want it to implicitly be null when not provided by the form
        // $data['nopol_kendaraan'] = null;
        $data['status_perjalanan'] = 'Permohonan';
        $data['jenis_operasional'] = 'Peminjaman';
        $data['status_operasional'] = 'Belum Ditetapkan';
        $data['pengemudi_id'] = null; // Now nullable, so this is fine

        try {
            \Log::info('Data for Perjalanan creation:', $data);
            $perjalanan = Perjalanan::create($data);

            $this->trackingUrl = url('/peminjaman/status/' . $perjalanan->token);
            $this->showSuccessModal = true;

            $this->currentStep = 1;
            $this->data = [];
            $this->form->fill([]);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
