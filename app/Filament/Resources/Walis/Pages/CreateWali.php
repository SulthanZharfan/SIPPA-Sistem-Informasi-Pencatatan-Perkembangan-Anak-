<?php

namespace App\Filament\Resources\Walis\Pages;

use App\Filament\Resources\Walis\WaliResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateWali extends CreateRecord
{
    protected static string $resource = WaliResource::class;

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
