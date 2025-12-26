<?php

namespace App\Filament\Guru\Resources\Presensis\Pages;

use App\Filament\Guru\Resources\Presensis\PresensiResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\EditRecordRedirect;

class EditPresensi extends EditRecordRedirect
{
    protected static string $resource = PresensiResource::class;

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

