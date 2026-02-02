<?php

namespace App\Filament\Resources\Gurus\Pages;

use App\Filament\Resources\Gurus\GuruResource;
use Filament\Actions\DeleteAction;
use App\Filament\Pages\EditRecordRedirect;
use Filament\Actions\Action;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EditGuru extends EditRecordRedirect
{
    protected static string $resource = GuruResource::class;

    public function getTitle(): string
    {
        return 'Edit Guru/Kepala Sekolah';
    }

    public function getHeading(): string
    {
        return 'Edit Guru/Kepala Sekolah';
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

    protected function mutateFormDataBeforeSave(array $data): array
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
