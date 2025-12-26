<?php

namespace App\Filament\Kepsek\Widgets;

use App\Models\PerkembanganFisik;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class SiswaPerkembanganFisikTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 5;

    public ?int $siswaId = null;
    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getTableQuery(): Builder
    {
        if (! $this->siswaId) {
            return PerkembanganFisik::query()->whereRaw('1 = 0');
        }

        return PerkembanganFisik::query()
            ->where('siswa_id', $this->siswaId)
            ->when($this->startDate, fn (Builder $q) => $q->whereDate('tanggal_ukur', '>=', $this->startDate))
            ->when($this->endDate, fn (Builder $q) => $q->whereDate('tanggal_ukur', '<=', $this->endDate))
            ->orderByDesc('tanggal_ukur');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('tanggal_ukur')
                ->label('Tanggal')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('tinggi_badan')
                ->label('TB')
                ->suffix(' cm')
                ->sortable(),

            TextColumn::make('berat_badan')
                ->label('BB')
                ->suffix(' kg')
                ->sortable(),

            TextColumn::make('lingkar_kepala')
                ->label('LK')
                ->suffix(' cm')
                ->sortable(),

            TextColumn::make('status_ringkas')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn (?string $state) => match ($state) {
                    'normal' => 'Normal',
                    'perlu_perhatian' => 'Perlu Perhatian',
                    default => $state ?? '-',
                })
                ->colors([
                    'success' => 'normal',
                    'warning' => 'perlu_perhatian',
                ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableHeading(): ?string
    {
        return 'Data Fisik';
    }
}
