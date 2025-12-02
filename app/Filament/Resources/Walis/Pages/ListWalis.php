<?php

namespace App\Filament\Resources\Walis\Pages;

use App\Filament\Resources\Walis\WaliResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWalis extends ListRecords
{
    protected static string $resource = WaliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
