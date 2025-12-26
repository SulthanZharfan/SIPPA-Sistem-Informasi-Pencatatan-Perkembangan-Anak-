<?php

namespace App\Filament\Resources\IndikatorPerkembangans\Pages;

use App\Filament\Resources\IndikatorPerkembangans\IndikatorPerkembanganResource;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\EditRecordRedirect;
use Filament\Actions\Action;

class EditIndikatorPerkembangan extends EditRecordRedirect
{
    protected static string $resource = IndikatorPerkembanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
}

