<?php

namespace App\Filament\Kepsek\Resources\PerkembanganKognitifs\Pages;

use App\Filament\Kepsek\Resources\PerkembanganKognitifs\PerkembanganKognitifResource;
use App\Models\PerkembanganKognitif;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPerkembanganKognitif extends ViewRecord
{
    protected static string $resource = PerkembanganKognitifResource::class;

    public function getHeading(): string
    {
        return 'Detail Kognitif';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Setujui')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status_persetujuan !== 'disetujui')
                ->requiresConfirmation()
                ->action(function () {
                    /** @var PerkembanganKognitif $record */
                    $record = $this->record;
                    $record->update(['status_persetujuan' => 'disetujui']);
                }),

            Actions\Action::make('revisi')
                ->label('Minta Revisi')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => $this->record->status_persetujuan !== 'revisi')
                ->requiresConfirmation()
                ->action(function () {
                    /** @var PerkembanganKognitif $record */
                    $record = $this->record;
                    $record->update(['status_persetujuan' => 'revisi']);
                }),
        ];
    }
}
