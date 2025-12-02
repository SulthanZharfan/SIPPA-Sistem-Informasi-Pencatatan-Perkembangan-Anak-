<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->maxLength(50),

                TextInput::make('nama')
                    ->label('Nama Siswa')
                    ->required()
                    ->maxLength(255),

                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->native(false)
                    ->required(),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'tahun')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('wali_id')
                    ->label('Wali Murid')
                    ->relationship('wali', 'nama')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
