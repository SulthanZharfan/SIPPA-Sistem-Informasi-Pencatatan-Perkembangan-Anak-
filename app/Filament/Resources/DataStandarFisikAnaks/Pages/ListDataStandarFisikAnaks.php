<?php

namespace App\Filament\Resources\DataStandarFisikAnaks\Pages;

use App\Filament\Resources\DataStandarFisikAnaks\DataStandarFisikAnakResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataStandarFisikAnaks extends ListRecords
{
    protected static string $resource = DataStandarFisikAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Data Standar Fisik'),
        ];
    }
}
