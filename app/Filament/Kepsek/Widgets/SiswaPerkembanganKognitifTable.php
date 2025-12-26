<?php

namespace App\Filament\Kepsek\Widgets;

use App\Filament\Kepsek\Resources\PerkembanganKognitifs\PerkembanganKognitifResource;
use App\Models\PerkembanganKognitif;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class SiswaPerkembanganKognitifTable extends TableWidget
{
    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 5;

    public ?int $siswaId = null;
    public ?int $tahunAjaranId = null;

    protected function getTableQuery(): Builder
    {
        if (! $this->siswaId) {
            return PerkembanganKognitif::query()->whereRaw('1 = 0');
        }

        return PerkembanganKognitif::query()
            ->with(['indikator'])
            ->where('siswa_id', $this->siswaId)
            ->when($this->tahunAjaranId, fn (Builder $q) => $q->where('tahun_ajaran_id', $this->tahunAjaranId))
            ->orderByDesc('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('indikator.aspek')
                ->label('Indikator')
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

    protected function getTableActions(): array
    {
        return [
            Action::make('detail')
                ->label('Detail')
                ->url(fn (PerkembanganKognitif $record) => PerkembanganKognitifResource::getUrl('view', ['record' => $record])),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Catatan Kognitif Terbaru';
    }
}
