<?php

namespace App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\Pages;

use App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use App\Filament\Pages\EditRecordRedirect;

class EditPertemuanPerkembanganFisik extends EditRecordRedirect
{
    protected static string $resource = PertemuanPerkembanganFisikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

