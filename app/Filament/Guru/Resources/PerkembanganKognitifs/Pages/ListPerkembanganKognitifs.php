<?php

namespace App\Filament\Guru\Resources\PerkembanganKognitifs\Pages;

use App\Filament\Guru\Resources\PerkembanganKognitifs\PerkembanganKognitifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPerkembanganKognitifs extends ListRecords
{
    protected static string $resource = PerkembanganKognitifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Catatan Kognitif'),
        ];
    }
}
