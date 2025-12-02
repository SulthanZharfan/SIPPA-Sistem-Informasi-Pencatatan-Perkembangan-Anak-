<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PerkembanganFisikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('siswa_id')
                    ->required()
                    ->numeric(),
                TextInput::make('guru_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tahun_ajaran_id')
                    ->required()
                    ->numeric(),
                TextInput::make('standar_id')
                    ->numeric(),
                TextInput::make('tinggi_badan')
                    ->required()
                    ->numeric(),
                TextInput::make('berat_badan')
                    ->required()
                    ->numeric(),
                TextInput::make('lingkar_kepala')
                    ->numeric(),
                DatePicker::make('tanggal_ukur')
                    ->required(),
                TextInput::make('umur_bulan')
                    ->numeric(),
                TextInput::make('foto'),
                TextInput::make('kategori_tb'),
                TextInput::make('kategori_bb'),
                TextInput::make('kategori_lk'),
                Select::make('status_persetujuan')
                    ->options(['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'revisi' => 'Revisi'])
                    ->default('menunggu')
                    ->required(),
            ]);
    }
}
