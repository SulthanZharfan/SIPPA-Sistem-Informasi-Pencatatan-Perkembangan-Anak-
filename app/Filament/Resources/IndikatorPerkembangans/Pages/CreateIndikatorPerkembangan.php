<?php

namespace App\Filament\Resources\IndikatorPerkembangans\Pages;

use App\Filament\Resources\IndikatorPerkembangans\IndikatorPerkembanganResource;
use App\Filament\Pages\CreateRecordRedirect;
use Filament\Actions\Action;

class CreateIndikatorPerkembangan extends CreateRecordRedirect
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

