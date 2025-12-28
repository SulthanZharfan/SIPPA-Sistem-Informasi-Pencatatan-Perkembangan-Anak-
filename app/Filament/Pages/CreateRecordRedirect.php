<?php

namespace App\Filament\Pages;

use Filament\Resources\Pages\CreateRecord;

class CreateRecordRedirect extends CreateRecord
{
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil disimpan';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

