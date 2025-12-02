<?php

namespace App\Filament\Resources\DataStandarFisikAnaks\Pages;

use App\Filament\Resources\DataStandarFisikAnaks\DataStandarFisikAnakResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataStandarFisikAnak extends EditRecord
{
    protected static string $resource = DataStandarFisikAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
