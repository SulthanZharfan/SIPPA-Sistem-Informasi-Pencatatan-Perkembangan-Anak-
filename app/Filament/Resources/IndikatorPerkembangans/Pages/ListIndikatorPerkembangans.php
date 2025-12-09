<?php

namespace App\Filament\Resources\IndikatorPerkembangans\Pages;

use App\Filament\Resources\IndikatorPerkembangans\IndikatorPerkembanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIndikatorPerkembangans extends ListRecords
{
    protected static string $resource = IndikatorPerkembanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Indikator Perkembangan'),
        ];
    }
}
