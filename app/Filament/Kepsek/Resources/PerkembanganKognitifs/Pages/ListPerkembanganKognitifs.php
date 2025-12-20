<?php

namespace App\Filament\Kepsek\Resources\PerkembanganKognitifs\Pages;

use App\Filament\Kepsek\Resources\PerkembanganKognitifs\PerkembanganKognitifResource;
use Filament\Resources\Pages\ListRecords;

class ListPerkembanganKognitifs extends ListRecords
{
    protected static string $resource = PerkembanganKognitifResource::class;

    protected function getHeaderActions(): array
    {
        // Kepsek tidak membuat data
        return [];
    }
}
