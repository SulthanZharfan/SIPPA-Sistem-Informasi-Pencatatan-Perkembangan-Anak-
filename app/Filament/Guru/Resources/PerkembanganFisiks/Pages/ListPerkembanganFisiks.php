<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Pages;

use App\Filament\Guru\Resources\PerkembanganFisiks\PerkembanganFisikResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPerkembanganFisiks extends ListRecords
{
    protected static string $resource = PerkembanganFisikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}