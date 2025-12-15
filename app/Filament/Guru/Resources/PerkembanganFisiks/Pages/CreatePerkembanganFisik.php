<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Pages;

use App\Filament\Guru\Resources\PerkembanganFisiks\PerkembanganFisikResource;
use App\Filament\Guru\Resources\PerkembanganFisiks\Schemas\PerkembanganFisikForm;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePerkembanganFisik extends CreateRecord
{
    protected static string $resource = PerkembanganFisikResource::class;

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['umur_bulan'] = PerkembanganFisikForm::calculateUmurBulan(
            $data['siswa_id'] ?? null,
            $data['tanggal_ukur'] ?? null,
        );

        return $data;
    }
}
