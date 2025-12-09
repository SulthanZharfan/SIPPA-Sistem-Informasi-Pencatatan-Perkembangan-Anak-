<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis\Pages;

use App\Filament\Guru\Resources\PertemuanPresensis\PertemuanPresensiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPertemuanPresensi extends EditRecord
{
    protected static string $resource = PertemuanPresensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
