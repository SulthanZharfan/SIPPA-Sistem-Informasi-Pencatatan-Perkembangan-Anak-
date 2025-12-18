<?php

namespace App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\Pages;

use App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use App\Models\PertemuanPerkembanganFisik;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPertemuanPerkembanganFisik extends ViewRecord
{
    protected static string $resource = PertemuanPerkembanganFisikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status !== 'approved')
                ->requiresConfirmation()
                ->action(function () {
                    /** @var PertemuanPerkembanganFisik $record */
                    $record = $this->record;

                    $record->update([
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $record->perkembanganFisiks()->update([
                        'status_persetujuan' => 'disetujui',
                    ]);
                }),

            Actions\Action::make('revisi')
                ->label('Minta Revisi')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => $this->record->status !== 'rejected')
                ->requiresConfirmation()
                ->action(function () {
                    /** @var PertemuanPerkembanganFisik $record */
                    $record = $this->record;

                    $record->update([
                        'status' => 'rejected',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $record->perkembanganFisiks()->update([
                        'status_persetujuan' => 'revisi',
                    ]);
                }),
        ];
    }
}
