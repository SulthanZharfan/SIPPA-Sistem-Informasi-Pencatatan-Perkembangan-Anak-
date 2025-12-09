<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Pages;

use App\Filament\Guru\Resources\PerkembanganFisiks\PerkembanganFisikResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPerkembanganFisik extends EditRecord
{
    protected static string $resource = PerkembanganFisikResource::class;

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
