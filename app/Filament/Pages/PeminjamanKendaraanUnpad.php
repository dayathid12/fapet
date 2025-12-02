<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use App\Models\Wilayah;
use App\Models\Perjalanan;
use App\Models\UnitKerja;
use App\Models\Kegiatan;
use Closure;

class PeminjamanKendaraanUnpad extends Page implements \Filament\Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.peminjaman-kendaraan-unpad';

    protected static ?string $title = 'Peminjaman Kendaraan Unpad';

    protected static bool $shouldSkipContentWrapper = true;

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }

    public ?array $data = [];

    public $currentStep = 1;

    public function mount(): void
    {
        $this->form->fill();
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    DateTimePicker::make('waktu_keberangkatan')->label('Waktu Keberangkatan')->required(),
                    DateTimePicker::make('waktu_kepulangan')->label('Waktu Kepulangan'),
                ])->visible(fn () => $this->currentStep === 1),
                Grid::make(2)->schema([
                    TextInput::make('lokasi_keberangkatan')->label('Lokasi Keberangkatan')->required(),
                    TextInput::make('jumlah_rombongan')->label('Jumlah Rombongan')->required()->numeric()->minValue(1),
                ])->visible(fn () => $this->currentStep === 1),
                Textarea::make('alamat_tujuan')->label('Alamat Tujuan')->required()->visible(fn () => $this->currentStep === 1),
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
                        ->extraAttributes(['class' => 'no-arrow'])
                        ->required(),
                    Select::make('tujuan_wilayah_id')
                        ->label('Kota Kabupaten')
                        ->options(Wilayah::all()->pluck('nama_wilayah', 'wilayah_id'))
                        ->searchable()
                        ->preload()
                        ->live(),
                        // ->afterStateUpdated(function (
                        //     \Filament\Forms\Set $set,
                        //     $state,
                        // ) {
                        //     $city = Wilayah::where('wilayah_id', $state)->first();
                        //     if ($city) {
                        //         $set('provinsi', $city->provinsi);
                        //     } else {
                        //         $set('provinsi', null);
                        //     }
                        // })
                        ->createOptionForm([
                            TextInput::make('nama_wilayah')
                                ->label('Nama Wilayah Baru')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('kota_kabupaten')
                                ->label('Kota/Kabupaten')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('provinsi')
                                ->label('Provinsi')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->required()
                        ->placeholder('Pilih kota kabupaten...'),
                ])->visible(fn () => $this->currentStep === 1),

                Select::make('unit_kerja_id')
                    ->label('Unit Kerja/Fakultas/UKM')
                    ->options(UnitKerja::all()->pluck('nama_unit_kerja', 'unit_kerja_id'))
                    ->searchable()
                    ->required()
                    ->visible(fn () => $this->currentStep === 2),
                TextInput::make('nama_pengguna')->label('Nama Pengguna')->required()->visible(fn () => $this->currentStep === 2),
                TextInput::make('kontak_pengguna')->label('Kontak Pengguna')->required()->visible(fn () => $this->currentStep === 2),
                Checkbox::make('use_same_info')
                    ->label('Gunakan informasi yang sama untuk Personil Perwakilan')
                    ->reactive()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state, Closure $get) {
                        if ($state) {
                            $set('nama_personil_perwakilan', $get('nama_pengguna'));
                            $set('kontak_pengguna_perwakilan', $get('kontak_pengguna'));
                        } else {
                            $set('nama_personil_perwakilan', null);
                            $set('kontak_pengguna_perwakilan', null);
                        }
                    })->visible(fn () => $this->currentStep === 2),
                TextInput::make('nama_personil_perwakilan')->label('Nama Personil Perwakilan')->required()->visible(fn () => $this->currentStep === 2),
                TextInput::make('kontak_pengguna_perwakilan')->label('Kontak Personil Perwakilan')->required()->visible(fn () => $this->currentStep === 2),
                Select::make('status_sebagai')
                    ->label('Status Sebagai')
                    ->options([
                        'Mahasiswa' => 'Mahasiswa',
                        'Dosen' => 'Dosen',
                        'Staf' => 'Staf',
                        'Lainnya' => 'Lainnya',
                    ])->required()->visible(fn () => $this->currentStep === 2),

                Select::make('tujuan_wilayah_id')
                    ->label('Kota Kabupaten')
                    ->relationship('wilayah', 'nama_wilayah')
                    ->searchable()
                    ->preload()
                    ->live(),
                    // ->afterStateUpdated(function (
                    //     \Filament\Forms\Set $set,
                    //     $state,
                    // ) {
                    //     $city = Wilayah::where('wilayah_id', $state)->first();
                    //     if ($city) {
                    //         $set('provinsi', $city->provinsi);
                    //     } else {
                    //         $set('provinsi', null);
                    //     }
                    // })
                    // ->createOptionForm([
                    //     TextInput::make('nama_wilayah')
                    //         ->label('Nama Wilayah Baru')
                    //         ->required()
                    //         ->maxLength(255),
                    //     TextInput::make('kota_kabupaten')
                    //         ->label('Kota/Kabupaten')
                    //         ->required()
                    //         ->maxLength(255),
                    //     TextInput::make('provinsi')
                    //         ->label('Provinsi')
                    //         ->required()
                    //         ->maxLength(255),
                    // ])
                    ->required()
                    ->placeholder('Pilih kota kabupaten...')
                    ->visible(fn () => $this->currentStep === 3),
                TextInput::make('provinsi')->label('Provinsi')->disabled()->visible(fn () => $this->currentStep === 3),
                Textarea::make('uraian_singkat_kegiatan')->label('Uraian Singkat Kegiatan')->visible(fn () => $this->currentStep === 3),
                Textarea::make('catatan_keterangan_tambahan')->label('Catatan/Keterangan Tambahan')->visible(fn () => $this->currentStep === 3),

                // Placeholder for document upload fields for step 4
            ])
            ->statePath('data');
    }

    public function nextStep(): void
    {
        if ($this->currentStep < 4) {
            $this->currentStep++;
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
        $data = $this->form->getState();

        // Mutate data before creation
        $data['jenis_kegiatan'] = $data['nama_kegiatan'];
        $data['nopol_kendaraan'] = null;
        $data['status_perjalanan'] = 'Permohonan';
        $data['jenis_operasional'] = 'Peminjaman';
        $data['status_operasional'] = 'Belum Ditetapkan';
        $data['pengemudi_id'] = null;

        Perjalanan::create($data);

        Notification::make()
            ->title('Permohonan berhasil diajukan!')
            ->success()
            ->send();

        $this->form->fill(); // Clear the form after submission
    }
}
