<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Schemas;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PerkembanganFisikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(2)->schema([
                    Select::make('siswa_id')
                        ->label('Siswa')
                        ->options(Siswa::orderBy('nama')->pluck('nama', 'id'))
                        ->searchable()
                        ->required(),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('tinggi_badan')
                        ->label('Tinggi Badan (cm)')
                        ->numeric()
                        ->step(0.1)
                        ->required(),

                    TextInput::make('berat_badan')
                        ->label('Berat Badan (kg)')
                        ->numeric()
                        ->step(0.1)
                        ->required(),

                    TextInput::make('lingkar_kepala')
                        ->label('Lingkar Kepala (cm)')
                        ->numeric()
                        ->step(0.1)
                        ->required(),
                ]),

                Grid::make(2)->schema([
                    DatePicker::make('tanggal_ukur')
                        ->label('Tanggal Ukur')
                        ->required(),

                    TextInput::make('umur_bulan')
                        ->label('Umur (bulan)')
                        ->numeric()
                        ->required(),
                ]),

                FileUpload::make('foto')
                    ->label('Foto (opsional)')
                    ->directory('foto-perkembangan-fisik')
                    ->image()
                    ->maxSize(2048)
                    ->columnSpanFull(),

                Hidden::make('guru_id')
                    ->default(fn () => auth()->id()),

                Hidden::make('tahun_ajaran_id')
                    ->default(fn () => TahunAjaran::where('is_active', 1)->first()?->id),

                Hidden::make('status_persetujuan')
                    ->default('menunggu'),
            ]);
    }
}
