<?php

namespace App\Filament\Kepsek\Resources\Siswas\Pages;

use App\Filament\Kepsek\Resources\Siswas\SiswaResource;
use App\Filament\Kepsek\Widgets\KepsekStudentsK1Table;
use App\Filament\Kepsek\Widgets\KepsekStudentsK2Table;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(1)
                ->schema([
                    Livewire::make(KepsekStudentsK1Table::class),
                    Livewire::make(KepsekStudentsK2Table::class),
                ])
                ->columns(1),
        ]);
    }
}
