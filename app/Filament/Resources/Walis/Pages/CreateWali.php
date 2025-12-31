<?php

namespace App\Filament\Resources\Walis\Pages;

use App\Filament\Resources\Walis\WaliResource;
use App\Filament\Pages\CreateRecordRedirect;
use App\Models\Wali;
use Filament\Actions\Action;

class CreateWali extends CreateRecordRedirect
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nama'] = Wali::sanitizeNama($data['nama'] ?? null);

        return $data;
    }
}

