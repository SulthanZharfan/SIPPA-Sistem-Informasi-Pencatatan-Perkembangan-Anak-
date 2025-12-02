<?php

namespace App\Filament\Resources\IndikatorPerkembangans\Pages;

use App\Filament\Resources\IndikatorPerkembangans\IndikatorPerkembanganResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIndikatorPerkembangan extends EditRecord
{
    protected static string $resource = IndikatorPerkembanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
