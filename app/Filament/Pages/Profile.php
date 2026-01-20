<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Password;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.profile';

    protected static ?string $navigationLabel = 'Profil';

    protected static ?string $title = 'Profil Saya';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        $staf = $user->staf;

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'id_nama' => $staf?->id_nama,
            'nama_staf' => $staf?->nama_staf,
            'gol_pangkat' => $staf?->gol_pangkat,
            'nip_staf' => $staf?->nip_staf,
            'status' => $staf?->status,
            'pendidikan_aktif' => $staf?->pendidikan_aktif,
            'wa_staf' => $staf?->wa_staf,
            'jabatan' => $staf?->jabatan,
            'tanggal_lahir' => $staf?->tanggal_lahir,
            'menuju_pensiun' => $staf?->menuju_pensiun,
            'kartu_pegawai' => $staf?->kartu_pegawai,
            'status_kepegawaian' => $staf?->status_kepegawaian,
            'tempat_lahir' => $staf?->tempat_lahir,
            'no_ktp' => $staf?->no_ktp,
            'no_npwp' => $staf?->no_npwp,
            'no_bpjs_kesehatan' => $staf?->no_bpjs_kesehatan,
            'no_bpjs_ketenagakerjaan' => $staf?->no_bpjs_ketenagakerjaan,
            'no_telepon' => $staf?->no_telepon,
            'alamat_rumah' => $staf?->alamat_rumah,
            'rekening' => $staf?->rekening,
            'nama_bank' => $staf?->nama_bank,
            'status_aplikasi' => $staf?->status_aplikasi,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->hidden()
                            ->currentPassword()
                            ->revealable(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->label('Password Baru')
                                    ->password()
                                    ->required()
                                    ->rule(Password::default())
                                    ->revealable()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->live()
                                    ->same('password_confirmation'),

                                Forms\Components\TextInput::make('password_confirmation')
                                    ->label('Konfirmasi Password Baru')
                                    ->password()
                                    ->required()
                                    ->revealable(),
                            ]),
                    ]),

                Forms\Components\Section::make('Informasi Staf')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('id_nama')
                                    ->label('ID Nama')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->hidden(),

                                Forms\Components\TextInput::make('nama_staf')
                                    ->label('Nama Staf')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('gol_pangkat')
                                    ->label('Golongan Pangkat')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('nip_staf')
                                    ->label('NIP Staf')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('jabatan')
                                    ->label('Jabatan')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->hidden(),

                                Forms\Components\TextInput::make('status_kepegawaian')
                                    ->label('Status Kepegawaian')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('pendidikan_aktif')
                                    ->label('Pendidikan Aktif')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('kartu_pegawai')
                                    ->label('Kartu Pegawai')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('wa_staf')
                                    ->label('WhatsApp')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->hidden(),

                                Forms\Components\TextInput::make('no_telepon')
                                    ->label('No Telepon')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('alamat_rumah')
                                    ->label('Alamat Rumah')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('no_ktp')
                                    ->label('No KTP')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('no_npwp')
                                    ->label('No NPWP')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('no_bpjs_kesehatan')
                                    ->label('No BPJS Kesehatan')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('no_bpjs_ketenagakerjaan')
                                    ->label('No BPJS Ketenagakerjaan')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\TextInput::make('rekening')
                                    ->label('Rekening')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('nama_bank')
                                    ->label('Nama Bank')
                                    ->maxLength(255),

                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->disabled(),

                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->maxLength(255)
                                    ->disabled(),

                                Forms\Components\Select::make('status_aplikasi')
                                    ->label('Status Aplikasi')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'non-aktif' => 'Non-Aktif',
                                    ])
                                    ->disabled()
                                    ->hidden(),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->submit('save'),
        ];
    }

    private function calculateRetirementCountdown(?string $tanggalLahir): string
    {
        if (!$tanggalLahir) {
            return 'Data tanggal lahir tidak tersedia';
        }

        try {
            $birthDate = \Carbon\Carbon::parse($tanggalLahir);
            $retirementDate = $birthDate->copy()->addYears(58); // PNS pensiun di usia 58 tahun
            $now = \Carbon\Carbon::now();

            if ($retirementDate->isPast()) {
                return 'Sudah pensiun';
            }

            $diff = $now->diff($retirementDate);

            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            $parts = [];

            if ($years > 0) {
                $parts[] = $years . ' tahun';
            }

            if ($months > 0) {
                $parts[] = $months . ' bulan';
            }

            if ($days > 0) {
                $parts[] = $days . ' hari';
            }

            return implode(' ', $parts) ?: 'Kurang dari 1 hari';

        } catch (\Exception $e) {
            return 'Error menghitung waktu pensiun';
        }
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $user = auth()->user();

            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'] ?? $user->password,
            ]);

            if ($user->staf) {
                $user->staf->update([
                    'id_nama' => $data['id_nama'],
                    'nama_staf' => $data['nama_staf'],
                    'gol_pangkat' => $data['gol_pangkat'],
                    'nip_staf' => $data['nip_staf'],
                    'status' => $data['status'],
                    'pendidikan_aktif' => $data['pendidikan_aktif'],
                    'wa_staf' => $data['wa_staf'],
                    'jabatan' => $data['jabatan'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'kartu_pegawai' => $data['kartu_pegawai'],
                    'status_kepegawaian' => $data['status_kepegawaian'],
                    'tempat_lahir' => $data['tempat_lahir'],
                    'no_ktp' => $data['no_ktp'],
                    'no_npwp' => $data['no_npwp'],
                    'no_bpjs_kesehatan' => $data['no_bpjs_kesehatan'],
                    'no_bpjs_ketenagakerjaan' => $data['no_bpjs_ketenagakerjaan'],
                    'no_telepon' => $data['no_telepon'],
                    'alamat_rumah' => $data['alamat_rumah'],
                    'rekening' => $data['rekening'],
                    'nama_bank' => $data['nama_bank'],
                    'status_aplikasi' => $data['status_aplikasi'],
                ]);
            }

            Notification::make()
                ->title('Profil berhasil diperbarui')
                ->success()
                ->send();

        } catch (Halt $exception) {
            return;
        }
    }
}
