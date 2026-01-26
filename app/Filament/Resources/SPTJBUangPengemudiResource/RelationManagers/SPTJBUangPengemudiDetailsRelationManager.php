<?php

namespace App\Filament\Resources\SPTJBUangPengemudiResource\RelationManagers;

use App\Models\Staf;
use App\Models\SPTJBUangPengemudiDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SPTJBUangPengemudiDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('sptjb_pengemudi_id')
                    ->default(fn () => $this->ownerRecord->id),
                Forms\Components\Hidden::make('no_sptjb')
                    ->default(fn () => $this->ownerRecord->no_sptjb),
                Forms\Components\TextInput::make('no')
                    ->label('No')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(fn () => static::getNextNo($this->ownerRecord->id)),
                Forms\Components\Select::make('nama')
                    ->label('Nama')
                    ->options(Staf::all()->pluck('nama_staf', 'nama_staf'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $staf = Staf::where('nama_staf', $state)->first();
                        if ($staf) {
                            $set('nomor_rekening', $staf->rekening);
                            $set('golongan', $staf->gol_pangkat);
                        }
                    })
                    ->required(),
                Forms\Components\TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tanggal_penugasan')
                    ->label('Tanggal Penugasan'),
                Forms\Components\TextInput::make('jumlah_hari')
                    ->label('Jumlah Hari')
                    ->numeric()
                    ->default(1) // Added default value
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $besaran = $get('besaran_uang_per_hari');
                        if ($state && $besaran) {
                            $set('jumlah_uang_diterima', $state * $besaran);
                        }
                    }),
                Forms\Components\TextInput::make('besaran_uang_per_hari')
                    ->label('Besaran uang / Hari (Rp)')
                    ->numeric()
                    ->default(150000) // Added default value
                    ->prefix('Rp')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $jumlahHari = $get('jumlah_hari');
                        if ($state && $jumlahHari) {
                            $set('jumlah_uang_diterima', $state * $jumlahHari);
                        }
                    }),
                Forms\Components\TextInput::make('jumlah_uang_diterima')
    ->label('Jumlah Uang Diterima')
    ->numeric()
    ->prefix('Rp')
    ->readOnly()
    ->dehydrated(),
                Forms\Components\TextInput::make('nomor_rekening')
                    ->label('Nomor Rekening')
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\TextInput::make('golongan')
                    ->label('Golongan')
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\TextInput::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $perjalanan = \App\Models\Perjalanan::where('no_surat_tugas', $state)->first();
                            if ($perjalanan) {
                                $besaran = 150000;
                                $set('besaran_uang_per_hari', $besaran);

                                if ($perjalanan->waktu_keberangkatan && $perjalanan->waktu_kepulangan) {
                                    $period = \Carbon\CarbonPeriod::create($perjalanan->waktu_keberangkatan, $perjalanan->waktu_kepulangan);
                                    $dates = [];
                                    foreach ($period as $date) {
                                        $dates[] = $date->format('j');
                                    }
                                    $jumlahHari = count($dates);
                                    $set('tanggal_penugasan', implode(',', $dates));
                                    $set('jumlah_hari', $jumlahHari);
                                    $set('jumlah_uang_diterima', 750000);
                                }
                            }
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->query(fn () => $this->getGroupedRecords())
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('Nomor Surat'),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan'),
                Tables\Columns\TextColumn::make('tanggal_penugasan')
                    ->label('Tanggal Penugasan'),
                Tables\Columns\TextColumn::make('jumlah_hari')
                    ->label('Jumlah Hari'),
                Tables\Columns\TextColumn::make('besaran_uang_per_hari')
                    ->label('Besaran uang / Hari (Rp)')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('jumlah_uang_diterima')
                    ->label('Jumlah Uang Diterima')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('nomor_rekening')
                    ->label('Nomor Rekening'),
                Tables\Columns\TextColumn::make('golongan')
                    ->label('Golongan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('fileSptjb')
                    ->label('File SPTJB')
                    ->modalHeading('File SPTJB')
                    ->modalContent(function () {
                        $sptjb = $this->getOwnerRecord();
                        return new HtmlString('<iframe src="' . route('sptjb.full.pdf', $sptjb->id) . '" width="100%" height="600px"></iframe>');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalWidth('7xl')
                    ->color('info')
                    ->icon('heroicon-o-document-text'),
                Action::make('tableSptjb')
                    ->label('Table SPTJB')
                    ->modalHeading('Table SPTJB')
                    ->modalContent(function () {
                        $sptjb = $this->getOwnerRecord();
                        return new HtmlString('<iframe src="' . route('sptjb.table.pdf', $sptjb->id) . '" width="100%" height="600px"></iframe>');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalWidth('7xl')
                    ->color('success')
                    ->icon('heroicon-o-table-cells')
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public function getTableRecordKey(Model $record): string
    {
        return $record->nama;
    }

    protected static function getNextNo($sptjbPengemudiId)
    {
        return SPTJBUangPengemudiDetail::where('sptjb_pengemudi_id', $sptjbPengemudiId)->count() + 1;
    }

    protected function getGroupedRecords()
    {
        // This now returns a query builder instead of a collection.
        return $this->getOwnerRecord()->details()
            ->select(
                'nama',
                DB::raw('ROW_NUMBER() OVER (ORDER BY MIN(id)) as no'),
                DB::raw('MIN(jabatan) as jabatan'),
                DB::raw('MIN(besaran_uang_per_hari) as besaran_uang_per_hari'),
                DB::raw('SUM(jumlah_hari) as jumlah_hari'),
                DB::raw('SUM(jumlah_uang_diterima) as jumlah_uang_diterima'),
                DB::raw('MIN(nomor_rekening) as nomor_rekening'),
                DB::raw('MIN(golongan) as golongan'),
                DB::raw("GROUP_CONCAT(DISTINCT nomor_surat ORDER BY CAST(nomor_surat AS UNSIGNED) SEPARATOR ',') as nomor_surat"),
                DB::raw("GROUP_CONCAT(DISTINCT tanggal_penugasan SEPARATOR ',') as tanggal_penugasan")
            )
            ->groupBy('nama')
            ->orderBy('no');
    }

    protected function resequenceNumbers(): void
    {
        // Get the parent SPTJBPengemudi ID
        $sptjbPengemudiId = $this->ownerRecord->id;

        // Fetch all details for this parent, ordered by id to maintain creation order
        $details = SPTJBUangPengemudiDetail::where('sptjb_pengemudi_id', $sptjbPengemudiId)
                                            ->orderBy('id')
                                            ->get();

        // Resequence the 'no' column
        foreach ($details as $index => $detail) {
            $detail->update(['no' => $index + 1]);
        }
    }
}
