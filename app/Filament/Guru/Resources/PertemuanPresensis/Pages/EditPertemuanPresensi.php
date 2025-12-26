<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis\Pages;

use App\Filament\Guru\Resources\PertemuanPresensis\PertemuanPresensiResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\EditRecordRedirect;

class EditPertemuanPresensi extends EditRecordRedirect
{
    protected static string $resource = PertemuanPresensiResource::class;

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

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}

