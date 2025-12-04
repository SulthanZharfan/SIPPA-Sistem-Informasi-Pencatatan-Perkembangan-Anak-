<?php

namespace App\Filament\Resources\DataStandarFisikAnaks\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DataStandarFisikAnakForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    TextInput::make('umur_bulan')
                        ->label('Umur (bulan)')
                        ->numeric()
                        ->required(),

                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options([
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan',
                        ])
                        ->native(false)
                        ->required()
                        ->columnSpan(2),
                ]),

                Grid::make(4)->schema([
                    TextInput::make('tb_min')
                        ->label('TB Min (cm)')
                        ->numeric()
                        ->required(),

                    TextInput::make('tb_max')
                        ->label('TB Max (cm)')
                        ->numeric()
                        ->required(),

                    TextInput::make('bb_min')
                        ->label('BB Min (kg)')
                        ->numeric()
                        ->required(),

                    TextInput::make('bb_max')
                        ->label('BB Max (kg)')
                        ->numeric()
                        ->required(),
                ]),

                Grid::make(4)->schema([
                    TextInput::make('lk_min')
                        ->label('LK Min (cm)')
                        ->numeric()
                        ->required(),

                    TextInput::make('lk_max')
                        ->label('LK Max (cm)')
                        ->numeric()
                        ->required(),
                ]),
            ]);
    }
}
