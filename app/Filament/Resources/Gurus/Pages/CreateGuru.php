<?php

namespace App\Filament\Resources\Gurus\Pages;

use App\Filament\Resources\Gurus\GuruResource;
use App\Filament\Pages\CreateRecordRedirect;
use Filament\Actions\Action;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateGuru extends CreateRecordRedirect
{
    protected static string $resource = GuruResource::class;

    public function getTitle(): string
    {
        return 'Tambah Guru/Kepala Sekolah';
    }

    public function getHeading(): string
    {
        return 'Tambah Guru/Kepala Sekolah';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }

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
        if (! empty($data['user_id'])) {
            DB::afterCommit(function () use ($data): void {
                $user = User::find($data['user_id']);
                if ($user && ! $user->hasRole('guru')) {
                    $user->assignRole('guru');
                }
            });
        }

        return $data;
    }
}
