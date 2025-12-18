<?php

namespace App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\Pages;

use App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use Filament\Resources\Pages\ListRecords;

class ListPertemuanPerkembanganFisiks extends ListRecords
{
    protected static string $resource = PertemuanPerkembanganFisikResource::class;

    protected function getHeaderActions(): array
    {
        // kepsek tidak buat pertemuan
        return [];
    }
}
