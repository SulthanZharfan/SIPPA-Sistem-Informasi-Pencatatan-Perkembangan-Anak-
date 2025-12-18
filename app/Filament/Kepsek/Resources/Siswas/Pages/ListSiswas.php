<?php

namespace App\Filament\Kepsek\Resources\Siswas\Pages;

use App\Filament\Kepsek\Resources\Siswas\SiswaResource;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
