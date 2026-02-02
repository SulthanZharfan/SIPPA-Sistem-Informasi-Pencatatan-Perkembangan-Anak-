<?php

namespace App\Filament\Resources\TahunAjarans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Validation\Rule;

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
                    ->maxLength(255)
                    ->rules(function (Get $get, $record) {
                        $semester = $get('semester');

                        if (! $semester) {
                            return [];
                        }

                        return [
                            Rule::unique('tahun_ajarans', 'tahun')
                                ->where('semester', $semester)
                                ->ignore($record),
                        ];
                    })
                    ->validationMessages([
                        'unique' => 'Kombinasi tahun ajaran dan semester sudah ada.',
                    ]),

                Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap'  => 'Genap',
                    ])
                    ->live()
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Hanya satu tahun ajaran yang boleh aktif.')
                    ->inline(false),
            ]);
    }
}
