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
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
                ->action(function (array $data): void {
                    // Unify the 'biaya' and 'bukti_path' fields
                    $data['biaya'] = $data['biaya_toll'] ?? $data['biaya_bbm'] ?? $data['biaya_parkir'] ?? null;
                    $data['bukti_path'] = $data['bukti_path_toll'] ?? $data['bukti_path_bbm'] ?? $data['bukti_path_parkir'] ?? null;

                    // Remove the temporary, category-specific fields
                    unset($data['biaya_toll'], $data['biaya_bbm'], $data['biaya_parkir']);
                    unset($data['bukti_path_toll'], $data['bukti_path_bbm'], $data['bukti_path_parkir']);

                    $this->rincianPengeluaran->rincianBiayas()->create($data);
                })
                ->form(fn(Form $form) => $this->getBiayaForm($form));
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
                ]),

            \Filament\Forms\Components\Section::make('Detail Toll')
                ->visible(fn ($get) => $get('tipe') === 'toll')
                ->schema([
                    TextInput::make('biaya_toll')->label('Jumlah Toll')->numeric()->prefix('Rp')->required(),
                    FileUpload::make('bukti_path_toll')
                        ->label('Upload Struk Toll')
                        ->directory('struk-toll')
                        ->image()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state && is_array($state) && count($state) > 0) {
                                $filePath = $state[0]; // Get the first uploaded file path
                                $extractedAmount = self::extractAmountFromReceipt($filePath);
                                if ($extractedAmount && is_numeric($extractedAmount)) {
                                    $set('biaya_toll', $extractedAmount);
                                }
                            }
                        }),
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

    public static function extractAmountFromReceipt(string $filePath): ?string
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            Log::error('GEMINI_API_KEY not set in .env');
            return null;
        }

        $fullPath = Storage::disk('public')->path($filePath);
        if (!file_exists($fullPath)) {
            Log::error('File not found: ' . $fullPath);
            return null;
        }

        $fileContents = base64_encode(file_get_contents($fullPath));
        $mimeType = mime_content_type($fullPath);

        $prompt = <<<PROMPT
Lihat gambar struk tol ini dengan teliti.
Ekstrak nominal harga tarif tol yang dibayarkan.
Cari teks "Rp" yang sejajar dengan kata "GOL", "TARIF", atau "TOTAL".
Abaikan Sisa Saldo, Saldo Kartu, atau nomor seri kartu (SN).
Kembalikan HANYA angka saja tanpa karakter lain, seperti "22000" untuk Rp 22.000.
Jika tidak yakin, kembalikan "uncertain".
PROMPT;

        try {
            $response = Http::post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data' => $fileContents
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'topK' => 1,
                        'topP' => 1,
                        'maxOutputTokens' => 50,
                    ]
                ]);

            if ($response->successful()) {
                $candidates = $response->json('candidates');
                if (!empty($candidates[0]['content']['parts'][0]['text'])) {
                    $text = $candidates[0]['content']['parts'][0]['text'];
                    $numericText = preg_replace('/[^0-9]/', '', $text);
                    return $numericText ?: null;
                }
            }

            Log::error('Gemini API call was not successful.', ['response' => $response->json()]);
            return null;

        } catch (\Exception $e) {
            Log::error('Gemini API call failed: ' . $e->getMessage());
            return null;
        }
    }

    public function rp($value): string
    {
        return 'Rp' . number_format($value, 0, ',', '.');
    }
}
