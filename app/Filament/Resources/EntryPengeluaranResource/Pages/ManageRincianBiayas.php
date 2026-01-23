<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\EntryPengeluaran;
use App\Models\RincianPengeluaran;
use App\Models\RincianBiaya;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use App\Services\GeminiReceiptExtractor; // Import Service Class
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Livewire\Component as Livewire;

class ManageRincianBiayas extends Page implements \Filament\Forms\Contracts\HasForms, \Filament\Infolists\Contracts\HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    protected static ?string $title = 'Tambah Rincian Biaya';

    protected static string $resource = EntryPengeluaranResource::class;

    protected static string $view = 'filament.resources.entry-pengeluaran-resource.pages.manage-rincian-biayas';

    public EntryPengeluaran $record;
    public RincianPengeluaran $rincianPengeluaran;
    public $bbm;
    public $toll;
    public $parkir;
    public $hideElements; // Deklarasikan properti public

    public function mount(EntryPengeluaran $record, $rincianPengeluaranId): void
    {
        $this->record = $record;
        $this->rincianPengeluaran = RincianPengeluaran::with([
            'perjalananKendaraan.perjalanan.unitKerja',
            'perjalananKendaraan.perjalanan.wilayah',
            'perjalananKendaraan.pengemudi',
            'perjalananKendaraan.kendaraan'
        ])->findOrFail($rincianPengeluaranId);

        $this->bbm = RincianBiaya::where('rincian_pengeluaran_id', $this->rincianPengeluaran->id)
            ->where('tipe', 'bbm')
            ->get();
        $this->toll = RincianBiaya::where('rincian_pengeluaran_id', $this->rincianPengeluaran->id)
            ->where('tipe', 'toll')
            ->get();
        $this->parkir = RincianBiaya::where('rincian_pengeluaran_id', $this->rincianPengeluaran->id)
            ->where('tipe', 'parkir')
            ->get();

        $this->hideElements = $this->record->hide_elements; // Inisialisasi dari model
    }

    public function toggleHideElements(): void
    {
        $this->record->hide_elements = !$this->record->hide_elements;
        $this->record->save();
        $this->hideElements = $this->record->hide_elements; // Update component property
        $this->dispatch('refresh');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $pk = $this->rincianPengeluaran->perjalananKendaraan;
        $perjalanan = $pk?->perjalanan;

        if (!$perjalanan) {
            return $infolist->state([])->schema([
                \Filament\Infolists\Components\Section::make('Data Perjalanan Tidak Lengkap')
                    ->description('Tidak dapat memuat detail perjalanan yang terkait.')
                    ->schema([
                        TextEntry::make('error')->default('Silakan periksa kembali data Rincian Pengeluaran.')
                    ])
            ]);
        }

        return $infolist
            ->record($this->rincianPengeluaran)
            ->state([
                'nomor_perjalanan' => $perjalanan->nomor_perjalanan,
                'nama_pengemudi' => $pk->pengemudi->nama_staf ?? '-',
                'waktu_berangkat' => $perjalanan->waktu_keberangkatan->format('d M Y, H:i'),
                'alamat_tujuan' => $perjalanan->alamat_tujuan,
                'unit_kerja' => $perjalanan->unitKerja->nama_unit_kerja ?? '-',
                'nopol_kendaraan' => $pk->kendaraan->nopol_kendaraan ?? '-',
                'kota_kabupaten' => $perjalanan->wilayah->nama_wilayah ?? '-',
            ])
            ->schema([
                \Filament\Infolists\Components\Section::make('Informasi Perjalanan')
                    ->collapsible()
                    ->collapsed()
                    ->extraAttributes(['class' => 'text-gray-600 dark:text-gray-300'])
                    ->columns(3) // Set columns for the main section
                    ->schema([
                        TextEntry::make('nomor_perjalanan')
                            ->label('Nomor Perjalanan')
                            ->weight(FontWeight::Bold)
                            ->copyable()
                            ->icon('heroicon-o-document-text'),
                        TextEntry::make('nama_pengemudi')
                            ->label('Nama Pengemudi')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-user'),
                        TextEntry::make('nopol_kendaraan')
                            ->label('Nomor Polisi Kendaraan')
                            ->icon('heroicon-o-truck'),
                        TextEntry::make('alamat_tujuan')
                            ->label('Alamat Tujuan')
                            ->icon('heroicon-o-map-pin'),
                        TextEntry::make('kota_kabupaten')
                            ->label('Kota/Kabupaten Tujuan')
                            ->icon('heroicon-o-map'),
                        TextEntry::make('waktu_berangkat')
                            ->label('Waktu Berangkat')
                            ->icon('heroicon-o-calendar-days'),
                        TextEntry::make('unit_kerja')
                            ->label('Unit Kerja/Fakultas/UKM')
                            ->icon('heroicon-o-building-office-2'),
                    ])
            ]);
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            \Filament\Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn (): string => static::getResource()::getUrl('edit', ['record' => $this->record])),
        ];

        // Check if user email is allowed
        $allowedEmails = ['dayat.hidayat@unpad.ac.id', 'alya.silvianti@unpad.ac.id'];
        if (in_array(auth()->user()->email, $allowedEmails)) {
            $actions[] = \Filament\Actions\Action::make('toggle_hide')
                ->label(fn () => $this->hideElements ? 'Hidup' : 'Edit')
                ->icon(fn () => $this->hideElements ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                ->color('warning')
                ->action(function (): void {
                    $this->toggleHideElements();
                });
        }

        if (!$this->hideElements) {
            $actions[] = Action::make('Tambah Biaya')
                ->label('Tambah Rincian Biaya')
                ->icon('heroicon-o-plus')
                ->action(function (array $data, array $arguments, Action $action, Form $form): void {
                    // Unify the 'biaya' and 'bukti_path' fields
                    $data['biaya'] = $data['biaya_toll'] ?? $data['biaya_bbm'] ?? $data['biaya_parkir'] ?? null;
                    $data['bukti_path'] = $data['bukti_path_toll'] ?? $data['bukti_path_bbm'] ?? $data['bukti_path_parkir'] ?? null;

                    // Remove the temporary, category-specific fields
                    unset($data['biaya_toll'], $data['biaya_bbm'], $data['biaya_parkir']);
                    unset($data['bukti_path_toll'], $data['bukti_path_bbm'], $data['bukti_path_parkir']);

                    $this->rincianPengeluaran->rincianBiayas()->create($data);

                    Notification::make()
                        ->title('Data berhasil disimpan')
                        ->success()
                        ->send();

                    // If 'create another' was clicked, reset the form and halt closing the modal
                    if ($arguments['another'] ?? false) {
                        $tipe = $data['tipe']; // Simpan nilai 'tipe'
                        $action->callAfter(); // Call any after() hooks
                        $form->fill(); // Reset form fields
                        $form->fill(['tipe' => $tipe]); // Setel kembali nilai 'tipe'
                        $action->halt(); // Prevent modal from closing
                    } else {
                        // Refresh the page to show the new item
                        $this->dispatch('refresh');
                    }
                })
                ->form(fn(Form $form) => $this->getBiayaForm($form))
                ->extraModalFooterActions(fn (Action $action): array => [
                    $action->makeModalSubmitAction('createAnother', arguments: ['another' => true])
                        ->label(__('filament-forms::components.select.actions.create_option.modal.actions.create_another.label')),
                ]);
        }

        return $actions;
    }



    private function getBiayaForm(Form $form): Form
    {
        return $form->schema([
            Select::make('tipe')
                ->label('Kategori')
                ->options([
                    'bbm' => 'BBM',
                    'toll' => 'Toll',
                    'parkir' => 'Parkir',
                ])
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    // Clear fields when tipe changes
                    if ($state !== 'bbm') {
                        $set('jenis_bbm', null);
                        $set('volume', null);
                    }
                }),

            \Filament\Forms\Components\Section::make('Detail BBM')
                ->visible(fn ($get) => $get('tipe') === 'bbm')
                ->schema([
                    TextInput::make('biaya_bbm')->label('Jumlah BBM')->numeric()->prefix('Rp')->required(),
                    Select::make('jenis_bbm')->label('Jenis BBM')->options([
                        'Pertalite' => 'Pertalite',
                        'Pertamax' => 'Pertamax',
                        'Pertamax Green 95' => 'Pertamax Green 95',
                        'Pertamax Turbo' => 'Pertamax Turbo',
                        'Shell Super' => 'Shell Super',
                        'Shell V-Power' => 'Shell V-Power',
                        'Shell V-Power Nitro+' => 'Shell V-Power Nitro+',
                        'BP 92' => 'BP 92',
                        'BP Ultimate' => 'BP Ultimate',
                        'Revvo 90' => 'Revvo 90',
                        'Revvo 92' => 'Revvo 92',
                        'Revvo 95' => 'Revvo 95',
                        'BioSolar (B35)' => 'BioSolar (B35)',
                        'Dexlite' => 'Dexlite',
                        'Pertamina Dex' => 'Pertamina Dex',
                        'Shell V-Power Diesel' => 'Shell V-Power Diesel',
                        'BP Ultimate Diesel' => 'BP Ultimate Diesel',
                        'Vivo Diesel Plus' => 'Vivo Diesel Plus',
                    ])->required(),
                    TextInput::make('volume')->label('Volume (Liter)')->numeric()->required(),
                    TextInput::make('deskripsi')->label('Kode ATM/Keterangan')->required(),
                    Checkbox::make('pertama_retail')->label('Pertamina Retail'),
                    FileUpload::make('bukti_path_bbm')
                        ->label('Upload Struk BBM')
                        ->directory('struk-bbm'),
                    // Bungkus Action di dalam Actions
                    Actions::make([
                        // Tombol Aksi untuk memicu ekstraksi BBM
                        \Filament\Forms\Components\Actions\Action::make('extract_bbm_details')
                            ->label('Ekstrak Detail dari Struk BBM')
                            ->icon('heroicon-o-sparkles')
                            ->action(function (callable $set, $get) {
                                $uploadedFiles = $get('bukti_path_bbm');
                                if (empty($uploadedFiles)) {
                                    Notification::make()->title('Gagal Ekstrak')->body('Silakan unggah gambar struk BBM terlebih dahulu.')->warning()->send();
                                    return;
                                }

                                // Ambil file pertama dari array, tanpa bergantung pada key numerik.
                                $firstFile = reset($uploadedFiles);
                                if (!$firstFile) {
                                    Notification::make()->title('Gagal Ekstrak')->body('File tidak valid atau tidak ditemukan.')->warning()->send();
                                    return;
                                }

                                $filePath = $firstFile->getRealPath();

                                // Prompt untuk Gemini AI
                                $prompt = "Anda adalah asisten ahli ekstraksi data dari struk SPBU.
Lihat gambar struk ini dan ekstrak informasi berikut:
1.  `jenis_bbm`: Nama produk bahan bakar yang dibeli (contoh: \"Pertalite\", \"Pertamax Turbo\", \"BioSolar\").
2.  `volume`: Jumlah liter yang diisi. Cari kata kunci seperti \"Volume\", \"Ltr\", atau \"Liter\".
3.  `total_biaya`: Jumlah total pembayaran. Cari kata kunci seperti \"Total\", \"Bayar\", atau \"Rp\".

Abaikan informasi lain seperti nomor SPBU, tanggal, atau sisa saldo.
Kembalikan hasilnya dalam format JSON yang valid. Contoh:
{
  \"jenis_bbm\": \"Pertamax\",
  \"volume\": 35.5,
  \"total_biaya\": 500000
}

Jika salah satu informasi tidak dapat ditemukan, kembalikan nilai `null` untuk kunci tersebut.";

                                (new GeminiReceiptExtractor())->extractAndFill($filePath, $prompt, $set);
                            })
                            ->modalHeading('Konfirmasi Ekstraksi BBM')
                            ->modalDescription('Sistem akan mencoba membaca detail dari gambar struk BBM yang diunggah. Lanjutkan?'),
                    ]),
                ]),

            \Filament\Forms\Components\Section::make('Detail Toll')
                ->visible(fn ($get) => $get('tipe') === 'toll')
                ->schema([
                    TextInput::make('biaya_toll')->label('Jumlah Toll')->numeric()->prefix('Rp')->required(),
                    FileUpload::make('bukti_path_toll')
                        ->label('Upload Struk Toll')
                        ->directory('struk-toll'),
                    // Bungkus Action di dalam Actions
                    \Filament\Forms\Components\Actions::make([
                        // Tombol Aksi untuk memicu ekstraksi
                        \Filament\Forms\Components\Actions\Action::make('extract_toll_amount')
                            ->label('Ekstrak Jumlah dari Struk')
                            ->icon('heroicon-o-sparkles')
                            ->action(function (callable $set, $get) {
                                $uploadedFiles = $get('bukti_path_toll');
                                if (empty($uploadedFiles)) {
                                    Notification::make()->title('Gagal Ekstrak')->body('Silakan unggah gambar struk terlebih dahulu.')->warning()->send();
                                    return;
                                }

                                // Ambil file pertama dari array.
                                $firstFile = reset($uploadedFiles);
                                if (!$firstFile) {
                                    Notification::make()->title('Gagal Ekstrak')->body('File tidak valid atau tidak ditemukan.')->warning()->send();
                                    return;
                                }

                                $filePath = $firstFile->getRealPath();

                                $extractor = new GeminiReceiptExtractor();
                                // Asumsi method extractAmount ada di dalam service dan berfungsi
                                $amount = $extractor->extractAmount($filePath);

                                if ($amount) {
                                    $set('biaya_toll', $amount);
                                    Notification::make()->title('Ekstraksi Berhasil')->body("Jumlah berhasil diekstrak: Rp " . number_format($amount))->success()->send();
                                } else {
                                    Notification::make()->title('Ekstraksi Gagal')->body('Tidak dapat menemukan jumlah pada struk. Mohon isi manual.')->danger()->send();
                                }
                            })
                            ->requiresConfirmation() // Memberi jeda agar loading indicator terlihat
                            ->modalHeading('Konfirmasi Ekstraksi')
                            ->modalDescription('Sistem akan mencoba membaca jumlah biaya dari gambar struk yang diunggah. Lanjutkan?')
                            ->modalSubmitActionLabel('Ya, Ekstrak'),
                    ]),
                ]),

            \Filament\Forms\Components\Section::make('Detail Parkir')
                ->visible(fn ($get) => $get('tipe') === 'parkir')
                ->schema([
                    TextInput::make('biaya_parkir')->label('Jumlah Parkir')->numeric()->prefix('Rp')->required(),
                    FileUpload::make('bukti_path_parkir')
                        ->label('Upload Bukti Parkir')
                        ->directory('bukti-parkir'),
                ]),
        ]);
    }

    public function rp($value): string
    {
        return 'Rp' . number_format($value, 0, ',', '.');
    }
}
