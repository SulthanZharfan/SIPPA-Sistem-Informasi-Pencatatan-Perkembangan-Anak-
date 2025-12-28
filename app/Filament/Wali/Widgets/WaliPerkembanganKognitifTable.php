<?php

namespace App\Filament\Wali\Widgets;

use App\Models\PerkembanganKognitif;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class WaliPerkembanganKognitifTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 5;

    public ?int $siswaId = null;
    public array $allowedSiswaIds = [];

    protected function getTableQuery(): Builder
    {
        if (! $this->siswaId || ! in_array($this->siswaId, $this->allowedSiswaIds, true)) {
            return PerkembanganKognitif::query()->whereRaw('1 = 0');
        }

        return PerkembanganKognitif::query()
            ->with(['indikator'])
            ->where('siswa_id', $this->siswaId)
            ->orderByDesc('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('indikator.aspek')
                ->label('Indikator')
                ->getStateUsing(function ($record): string {
                    $indikator = $record?->indikator;
                    $label = $indikator?->aspek ?: $indikator?->deskripsi ?: '-';

                    if (str_starts_with($label, 'Berisikan Penjelasan ')) {
                        $label = substr($label, strlen('Berisikan Penjelasan '));
                    }

                    return $label;
                })
                ->limit(40)
                ->tooltip(fn ($record) => $record?->indikator?->deskripsi),

            TextColumn::make('created_at')
                ->label('Tanggal')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('status_persetujuan')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn (?string $state) => match ($state) {
                    'disetujui' => 'Disetujui',
                    'menunggu' => 'Menunggu',
                    'revisi' => 'Revisi',
                    default => $state ?? '-',
                })
                ->colors([
                    'success' => 'disetujui',
                    'warning' => 'menunggu',
                    'danger' => 'revisi',
                ]),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Catatan Kognitif Terbaru';
    }
}
