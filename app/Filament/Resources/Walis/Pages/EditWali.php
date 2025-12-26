<?php

namespace App\Filament\Resources\Walis\Pages;

use App\Filament\Resources\Walis\WaliResource;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\EditRecordRedirect;
use Filament\Actions\Action;

class EditWali extends EditRecordRedirect
{
    protected static string $resource = WaliResource::class;

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

