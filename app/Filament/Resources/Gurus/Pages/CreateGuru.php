<?php

namespace App\Filament\Resources\Gurus\Pages;

use App\Filament\Resources\Gurus\GuruResource;
use App\Filament\Pages\CreateRecordRedirect;
use Filament\Actions\Action;

class CreateGuru extends CreateRecordRedirect
{
    protected static string $resource = GuruResource::class;

    public function getTitle(): string
    {
        return 'Tambah Guru';
    }

    public function getHeading(): string
    {
        return 'Tambah Guru';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }

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
