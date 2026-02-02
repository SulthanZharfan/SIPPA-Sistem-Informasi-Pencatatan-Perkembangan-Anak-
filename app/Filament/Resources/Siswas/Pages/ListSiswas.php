<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Filament\Resources\Siswas\Widgets\SiswaKelasTable;
use App\Models\Kelas;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    public function content(Schema $schema): Schema
    {
        $kelasList = Kelas::query()
            ->with('tahunAjaran')
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $components = $kelasList->map(function (Kelas $kelas) {
            return Livewire::make(SiswaKelasTable::class, fn () => [
                'kelasId' => $kelas->id,
                'kelasNama' => $kelas->nama,
                'kelasTingkat' => $kelas->tingkat,
            ])->key('siswa-kelas-' . $kelas->id);
        })->all();

        return $schema->components($components);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Siswa'),
        ];
    }
}
