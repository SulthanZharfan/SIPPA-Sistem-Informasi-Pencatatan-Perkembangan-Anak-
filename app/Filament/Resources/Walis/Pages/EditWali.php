<?php

namespace App\Filament\Resources\Walis\Pages;

use App\Filament\Resources\Walis\WaliResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWali extends EditRecord
{
    protected static string $resource = WaliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
