<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis\Pages;

use App\Filament\Guru\Resources\PertemuanPresensis\PertemuanPresensiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPertemuanPresensis extends ListRecords
{
    protected static string $resource = PertemuanPresensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
