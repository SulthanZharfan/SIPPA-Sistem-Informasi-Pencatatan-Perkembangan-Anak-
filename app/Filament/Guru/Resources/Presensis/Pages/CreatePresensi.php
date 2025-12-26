<?php

namespace App\Filament\Guru\Resources\Presensis\Pages;

use App\Filament\Guru\Resources\Presensis\PresensiResource;
use Filament\Actions\Action;
use App\Filament\Pages\CreateRecordRedirect;

class CreatePresensi extends CreateRecordRedirect
{
    protected static string $resource = PresensiResource::class;

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

