<?php

namespace App\Filament\Resources\DataStandarFisikAnaks\Pages;

use App\Filament\Resources\DataStandarFisikAnaks\DataStandarFisikAnakResource;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\EditRecordRedirect;
use Filament\Actions\Action;

class EditDataStandarFisikAnak extends EditRecordRedirect
{
    protected static string $resource = DataStandarFisikAnakResource::class;

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

