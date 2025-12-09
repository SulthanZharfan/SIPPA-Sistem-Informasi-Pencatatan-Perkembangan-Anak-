<?php

namespace App\Filament\Resources\IndikatorPerkembangans\Pages;

use App\Filament\Resources\IndikatorPerkembangans\IndikatorPerkembanganResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateIndikatorPerkembangan extends CreateRecord
{
    protected static string $resource = IndikatorPerkembanganResource::class;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Buat');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Buat & buat baru');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
}
