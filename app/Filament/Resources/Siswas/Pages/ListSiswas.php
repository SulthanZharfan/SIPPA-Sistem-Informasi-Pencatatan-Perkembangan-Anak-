<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Filament\Resources\Siswas\Widgets\SiswaK1Table;
use App\Filament\Resources\Siswas\Widgets\SiswaK2Table;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Livewire::make(SiswaK1Table::class)->key('siswa-k1-table'),
            Livewire::make(SiswaK2Table::class)->key('siswa-k2-table'),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Siswa'),
        ];
    }
}
