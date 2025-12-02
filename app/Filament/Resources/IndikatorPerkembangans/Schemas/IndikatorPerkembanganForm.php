<?php

namespace App\Filament\Resources\IndikatorPerkembangans\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IndikatorPerkembanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('aspek')
                    ->label('Aspek Perkembangan')
                    ->placeholder('Misal: Kognitif, Bahasa, Sosial-Emosional, Kemandirian')
                    ->required()
                    ->maxLength(255),

                Textarea::make('deskripsi')
                    ->label('Deskripsi Indikator')
                    ->rows(4)
                    ->maxLength(65535),
            ]);
    }
}
