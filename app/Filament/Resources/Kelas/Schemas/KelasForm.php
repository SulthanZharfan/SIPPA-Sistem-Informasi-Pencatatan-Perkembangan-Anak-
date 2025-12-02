<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('nama')
                    ->label('Nama Kelas')
                    ->placeholder('Misal: A1, B2, TK A')
                    ->required()
                    ->maxLength(255),

                TextInput::make('tingkat')
                    ->label('Tingkat Kelas')
                    ->placeholder('Misal: Kelompok A, Kelompok B')
                    ->required()
                    ->maxLength(255),

                Select::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'tahun') // pakai accessor label di model TahunAjaran
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
