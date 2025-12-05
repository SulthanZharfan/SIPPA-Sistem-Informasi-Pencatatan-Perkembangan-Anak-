<?php

namespace App\Filament\Guru\Resources\Presensis\Pages;

use App\Filament\Guru\Resources\Presensis\PresensiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPresensi extends EditRecord
{
    protected static string $resource = PresensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
