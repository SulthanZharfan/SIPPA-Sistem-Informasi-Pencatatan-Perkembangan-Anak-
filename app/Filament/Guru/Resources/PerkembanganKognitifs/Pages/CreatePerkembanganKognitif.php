<?php

namespace App\Filament\Guru\Resources\PerkembanganKognitifs\Pages;

use App\Filament\Guru\Resources\PerkembanganKognitifs\PerkembanganKognitifResource;
use Filament\Actions\Action;
use App\Filament\Pages\CreateRecordRedirect;

class CreatePerkembanganKognitif extends CreateRecordRedirect
{
    protected static string $resource = PerkembanganKognitifResource::class;

    public function getTitle(): string
    {
        return 'Buat Catatan Perkembangan Kognitif';
    }

    public function getHeading(): string
    {
        return 'Buat Catatan Perkembangan Kognitif';
    }

    public function getBreadcrumb(): string
    {
        return 'Buat Catatan';
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

