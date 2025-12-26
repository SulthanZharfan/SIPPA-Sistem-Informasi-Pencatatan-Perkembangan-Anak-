<?php

namespace App\Filament\Resources\DataStandarFisikAnaks\Pages;

use App\Filament\Resources\DataStandarFisikAnaks\DataStandarFisikAnakResource;
use Filament\Actions\Action;
use App\Filament\Pages\CreateRecordRedirect;

class CreateDataStandarFisikAnak extends CreateRecordRedirect
{
    protected static string $resource = DataStandarFisikAnakResource::class;

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

