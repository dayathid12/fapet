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
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Closure;

class PeminjamanKendaraanUnpad extends Page implements \Filament\Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.peminjaman-kendaraan-unpad';

    protected static ?string $title = 'Peminjaman Kendaraan Unpad';

    public ?array $data = [];

    protected static bool $shouldRegisterNavigation = false;

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
                    DateTimePicker::make('waktu_kepulangan')->label('Waktu Kepulangan')->nullable(), // Changed to nullable
                ]),
                TextInput::make('lokasi_keberangkatan')->label('Lokasi Keberangkatan')->required(),
                TextInput::make('jumlah_rombongan')->label('Jumlah Rombongan')->required()->numeric()->minValue(1),
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
                    ])->required(),
                Textarea::make('alamat_tujuan')->label('Alamat Tujuan')->required(),
                Select::make('unit_kerja_id')
                    ->label('Unit Kerja/Fakultas/UKM')
                    ->options(UnitKerja::all()->pluck('nama_unit_kerja', 'unit_kerja_id'))
                    ->searchable()
                    ->required(),
                TextInput::make('nama_pengguna')->label('Nama Pengguna')->required(),
                TextInput::make('kontak_pengguna')->label('Kontak Pengguna')->required(),
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
                    }),
                TextInput::make('nama_personil_perwakilan')->label('Nama Personil Perwakilan')->required(),
                TextInput::make('kontak_pengguna_perwakilan')->label('Kontak Personil Perwakilan')->required(),
                Select::make('status_sebagai')
                    ->label('Status Sebagai')
                    ->options([
                        'Mahasiswa' => 'Mahasiswa',
                        'Dosen' => 'Dosen',
                        'Staf' => 'Staf',
                        'Lainnya' => 'Lainnya',
                    ])->required(),
                Select::make('tujuan_wilayah_id')
                    ->label('Kota Kabupaten')
                    ->options(Wilayah::all()->pluck('nama_wilayah', 'wilayah_id'))
                    ->reactive()
                    ->searchable()
                    ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                        if ($state) {
                            $wilayah = Wilayah::find($state);
                            if ($wilayah) {
                                $set('provinsi', $wilayah->provinsi);
                            }
                        } else {
                            $set('provinsi', null);
                        }
                    }),
                TextInput::make('provinsi')->label('Provinsi')->disabled(),
                Textarea::make('uraian_singkat_kegiatan')->label('Uraian Singkat Kegiatan')->nullable(),
                Textarea::make('catatan_keterangan_tambahan')->label('Catatan/Keterangan Tambahan')->nullable(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        try {
            $data = $this->form->getState();

            // Validate the form data
            $this->validate([
                'data.waktu_keberangkatan' => 'required|date',
                'data.waktu_kepulangan' => 'nullable|date|after_or_equal:data.waktu_keberangkatan',
                'data.lokasi_keberangkatan' => 'required|string|max:255',
                'data.jumlah_rombongan' => 'required|numeric|min:1',
                'data.nama_kegiatan' => 'required|string|max:255',
                'data.alamat_tujuan' => 'required|string|max:255',
                'data.unit_kerja_id' => 'required|exists:unit_kerjas,unit_kerja_id',
                'data.nama_pengguna' => 'required|string|max:255',
                'data.kontak_pengguna' => 'required|string|max:255',
                'data.nama_personil_perwakilan' => 'required|string|max:255',
                'data.kontak_pengguna_perwakilan' => 'required|string|max:255',
                'data.status_sebagai' => 'required|string|max:255',
                'data.tujuan_wilayah_id' => 'required|exists:wilayahs,wilayah_id',
                'data.provinsi' => 'required|string|max:255',
                'data.uraian_singkat_kegiatan' => 'nullable|string',
                'data.catatan_keterangan_tambahan' => 'nullable|string',
            ]);

            Perjalanan::create([
                'waktu_keberangkatan' => Carbon::parse($data['waktu_keberangkatan']),
                'waktu_kepulangan' => isset($data['waktu_kepulangan']) ? Carbon::parse($data['waktu_kepulangan']) : null,
                'lokasi_keberangkatan' => $data['lokasi_keberangkatan'],
                'jumlah_rombongan' => $data['jumlah_rombongan'],
                'nama_kegiatan' => $data['nama_kegiatan'],
                'alamat_tujuan' => $data['alamat_tujuan'],
                'unit_kerja_id' => $data['unit_kerja_id'],
                'nama_pengguna' => $data['nama_pengguna'],
                'kontak_pengguna' => $data['kontak_pengguna'],
                'nama_personil_perwakilan' => $data['nama_personil_perwakilan'],
                'kontak_pengguna_perwakilan' => $data['kontak_pengguna_perwakilan'],
                'status_sebagai' => $data['status_sebagai'],
                'tujuan_wilayah_id' => $data['tujuan_wilayah_id'],
                'provinsi' => $data['provinsi'],
                'uraian_singkat_kegiatan' => $data['uraian_singkat_kegiatan'],
                'catatan_keterangan_tambahan' => $data['catatan_keterangan_tambahan'],
                'status_perjalanan' => 'pending', // Default status for new submissions
                // Add any other default fields needed for Perjalanan
            ]);

            Notification::make()
                ->title('Pengajuan perjalanan berhasil dikirim!')
                ->success()
                ->send();

            $this->form->fill(); // Clear the form after successful submission

        } catch (\Illuminate\Validation\ValidationException $e) {
            Notification::make()
                ->title('Terjadi kesalahan validasi.')
                ->danger()
                ->body('Periksa kembali input Anda dan coba lagi.')
                ->send();
            Log::error('Validation error in Peminjaman Kendaraan Unpad form: ' . $e->getMessage(), $e->errors());
        } catch (\Throwable $e) {
            Log::error('Error submitting Peminjaman Kendaraan Unpad form: ' . $e->getMessage());
            Notification::make()
                ->title('Terjadi kesalahan saat mengirim formulir.')
                ->danger()
                ->body('Silakan coba lagi. Jika masalah berlanjut, hubungi administrator.')
                ->send();
        }
    }
}