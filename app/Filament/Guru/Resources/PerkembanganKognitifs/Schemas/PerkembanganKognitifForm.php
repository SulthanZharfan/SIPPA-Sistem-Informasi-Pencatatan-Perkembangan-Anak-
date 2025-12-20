<?php

namespace App\Filament\Guru\Resources\PerkembanganKognitifs\Schemas;

use App\Models\IndikatorPerkembangan;
use App\Models\TahunAjaran;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                        ->relationship(
                            name: 'siswa',
                            titleAttribute: 'nama',
                            modifyQueryUsing: function (Builder $query) {
                                $guruId = Auth::user()?->guru?->id ?? 0;

                                $query->whereHas('kelas', fn (Builder $kelas) => $kelas->where('guru_id', $guruId));
                            }
                        )
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('indikator_id')
                        ->label('Indikator Perkembangan')
                        ->options(IndikatorPerkembangan::orderBy('aspek')->pluck('aspek', 'id'))
                        ->searchable()
                        ->required(),
                ]),

                Textarea::make('narasi')
                    ->label('Narasi Perkembangan')
                    ->rows(10)
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('foto')
                    ->label('Foto (opsional)')
                    ->directory('foto-perkembangan-kognitif')
                    ->image()
                    ->maxSize(2048)
                    ->columnSpanFull(),

                Hidden::make('guru_id')
                    ->default(fn () => Auth::user()?->guru?->id),

                Hidden::make('tahun_ajaran_id')
                    ->default(fn () => TahunAjaran::where('is_active', 1)->first()?->id),

                Hidden::make('status_persetujuan')
                    ->default('menunggu'),
            ]);
    }
}
