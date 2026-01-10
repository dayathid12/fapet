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
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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
        return [
            \Filament\Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn (): string => static::getResource()::getUrl('edit', ['record' => $this->record])),
            Action::make('Tambah Biaya')
                ->label('Tambah Rincian Biaya')
                ->icon('heroicon-o-plus')
                ->action(function (array $data): void {
                    $this->rincianPengeluaran->rincianBiayas()->create($data);
                })
                ->form(fn(Form $form) => $this->getBiayaForm($form)),
        ];
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
                ->reactive()
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
                    TextInput::make('biaya')->label('Jumlah BBM')->numeric()->prefix('Rp')->required(),
                    Select::make('jenis_bbm')->label('Jenis BBM')->options(['Dexlite' => 'Dexlite', 'Pertamax' => 'Pertamax', 'Lainnya' => 'Lainnya'])->required(),
                    TextInput::make('volume')->label('Volume (Liter)')->numeric()->required(),
                    TextInput::make('deskripsi')->label('Kode ATM/Keterangan')->required(),
                    FileUpload::make('bukti_path')
                        ->label('Upload Struk BBM')
                        ->directory('struk-bbm')
                        ->extraAttributes([
                            'x-on:change' => 'processImage($event, $wire)',
                            'x-data' => 'ocrHandler', // Initialize Alpine component here
                        ]),
                ]),

            \Filament\Forms\Components\Section::make('Detail Toll')
                ->visible(fn ($get) => $get('tipe') === 'toll')
                ->schema([
                    TextInput::make('biaya')->label('Jumlah Toll')->numeric()->prefix('Rp')->required(),
                    TextInput::make('deskripsi')->label('Kode Kartu Toll/Gerbang')->required(),
                    FileUpload::make('bukti_path')
                        ->label('Upload Struk Toll')
                        ->directory('struk-toll')
                        ->extraAttributes([
                            'x-on:change' => 'processImage($event, $wire)',
                            'x-data' => 'ocrHandler', // Initialize Alpine component here
                        ]),
                ]),

            \Filament\Forms\Components\Section::make('Detail Parkir')
                ->visible(fn ($get) => $get('tipe') === 'parkir')
                ->schema([
                    TextInput::make('biaya')->label('Jumlah Parkir')->numeric()->prefix('Rp')->required(),
                    TextInput::make('deskripsi')->label('Lokasi Parkir')->required(),
                    FileUpload::make('bukti_path')
                        ->label('Upload Bukti Parkir')
                        ->directory('bukti-parkir')
                        ->extraAttributes([
                            'x-on:change' => 'processImage($event, $wire)',
                            'x-data' => 'ocrHandler', // Initialize Alpine component here
                        ]),
                ]),
        ]);
    }

    public function rp($value): string
    {
        return 'Rp' . number_format($value, 0, ',', '.');
    }
}
