<?php

namespace App\Filament\Guru\Resources\PerkembanganKognitifs\Schemas;

use App\Models\Siswa;
use App\Models\IndikatorPerkembangan;
use App\Models\TahunAjaran;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PerkembanganKognitifForm
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

                    Select::make('indikator_id')
                        ->label('Indikator Perkembangan')
                        ->options(IndikatorPerkembangan::orderBy('aspek')->pluck('aspek', 'id'))
                        ->searchable()
                        ->required(),
                ]),

                Textarea::make('narasi')
                    ->label('Narasi Perkembangan')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('foto')
                    ->label('Foto (opsional)')
                    ->directory('foto-perkembangan-kognitif')
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
