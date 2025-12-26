<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Pages;

use App\Filament\Guru\Resources\PerkembanganFisiks\PerkembanganFisikResource;
use App\Filament\Guru\Resources\PerkembanganFisiks\Schemas\PerkembanganFisikForm;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\EditRecordRedirect;

class EditPerkembanganFisik extends EditRecordRedirect
{
    protected static string $resource = PerkembanganFisikResource::class;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['umur_bulan'] = PerkembanganFisikForm::calculateUmurBulan(
            $data['siswa_id'] ?? null,
            $data['tanggal_ukur'] ?? null,
        );

        return $data;
    }
}

