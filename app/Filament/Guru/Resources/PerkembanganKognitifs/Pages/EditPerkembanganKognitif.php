<?php

namespace App\Filament\Guru\Resources\PerkembanganKognitifs\Pages;

use App\Filament\Guru\Resources\PerkembanganKognitifs\PerkembanganKognitifResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPerkembanganKognitif extends EditRecord
{
    protected static string $resource = PerkembanganKognitifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
}
