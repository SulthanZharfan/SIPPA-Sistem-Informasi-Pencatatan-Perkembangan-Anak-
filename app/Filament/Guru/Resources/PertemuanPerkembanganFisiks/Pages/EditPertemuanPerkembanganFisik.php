<?php

namespace App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Pages;

use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\EditRecordRedirect;

class EditPertemuanPerkembanganFisik extends EditRecordRedirect
{
    protected static string $resource = PertemuanPerkembanganFisikResource::class;

    public function getHeading(): string
    {
        return 'Edit Pertemuan';
    }

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

