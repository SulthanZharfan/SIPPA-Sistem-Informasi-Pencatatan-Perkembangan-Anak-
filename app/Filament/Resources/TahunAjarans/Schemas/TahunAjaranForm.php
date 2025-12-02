<?php

namespace App\Filament\Resources\TahunAjarans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TahunAjaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('tahun')
                    ->label('Tahun Ajaran')
                    ->placeholder('2025/2026')
                    ->required()
                    ->maxLength(255),

                Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap'  => 'Genap',
                    ])
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Hanya satu tahun ajaran yang boleh aktif.')
                    ->inline(false),
            ]);
    }
}
