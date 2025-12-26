<?php

namespace App\Filament\Kepsek\Resources\PerkembanganKognitifs\Pages;

use App\Filament\Kepsek\Resources\PerkembanganKognitifs\PerkembanganKognitifResource;
use App\Filament\Kepsek\Widgets\KepsekPerkembanganKognitifTable;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class ListPerkembanganKognitifs extends ListRecords
{
    protected static string $resource = PerkembanganKognitifResource::class;

    protected function getHeaderActions(): array
    {
        // Kepsek tidak membuat data
        return [];
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Livewire::make(KepsekPerkembanganKognitifTable::class, fn () => [
                'kelasTingkat' => 'K1',
            ])->key('kognitif-k1'),
            Livewire::make(KepsekPerkembanganKognitifTable::class, fn () => [
                'kelasTingkat' => 'K2',
            ])->key('kognitif-k2'),
        ]);
    }
}
