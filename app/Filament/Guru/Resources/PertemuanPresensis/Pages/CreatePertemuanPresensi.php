<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis\Pages;

use App\Filament\Guru\Resources\PertemuanPresensis\PertemuanPresensiResource;
use Filament\Actions\Action;
use App\Filament\Pages\CreateRecordRedirect;

class CreatePertemuanPresensi extends CreateRecordRedirect
{
    protected static string $resource = PertemuanPresensiResource::class;

    public function getTitle(): string
    {
        return 'Buat Pertemuan Presensi';
    }

    public function getHeading(): string
    {
        return 'Buat Pertemuan Presensi';
    }

    public function getBreadcrumb(): string
    {
        return 'Buat Pertemuan';
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

