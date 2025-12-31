<?php

namespace App\Filament\Resources\IndikatorPerkembangans\Pages;

use App\Filament\Resources\IndikatorPerkembangans\IndikatorPerkembanganResource;
use App\Filament\Pages\CreateRecordRedirect;
use Filament\Actions\Action;

class CreateIndikatorPerkembangan extends CreateRecordRedirect
{
    protected static string $resource = IndikatorPerkembanganResource::class;

    public function getTitle(): string
    {
        return 'Tambah Indikator Perkembangan';
    }

    public function getHeading(): string
    {
        return 'Tambah Indikator Perkembangan';
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
