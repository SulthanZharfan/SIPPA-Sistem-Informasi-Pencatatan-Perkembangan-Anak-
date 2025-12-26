<?php

namespace App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Pages;

use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use Filament\Actions\Action;
use App\Filament\Pages\CreateRecordRedirect;

class CreatePertemuanPerkembanganFisik extends CreateRecordRedirect
{
    protected static string $resource = PertemuanPerkembanganFisikResource::class;

    public function getTitle(): string
    {
        return 'Buat Pertemuan Perkembangan Fisik';
    }

    public function getHeading(): string
    {
        return 'Buat Pertemuan Perkembangan Fisik';
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
}

