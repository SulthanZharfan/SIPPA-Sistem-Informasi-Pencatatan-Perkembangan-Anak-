<?php

namespace App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\Pages;

use App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use App\Filament\Kepsek\Widgets\KepsekPertemuanPerkembanganFisikTable;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class ListPertemuanPerkembanganFisiks extends ListRecords
{
    protected static string $resource = PertemuanPerkembanganFisikResource::class;

    protected function getHeaderActions(): array
    {
        // kepsek tidak buat pertemuan
        return [];
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Livewire::make(KepsekPertemuanPerkembanganFisikTable::class, fn () => [
                'kelasTingkat' => 'K1',
            ])->key('fisik-k1'),
            Livewire::make(KepsekPertemuanPerkembanganFisikTable::class, fn () => [
                'kelasTingkat' => 'K2',
            ])->key('fisik-k2'),
        ]);
    }
}
