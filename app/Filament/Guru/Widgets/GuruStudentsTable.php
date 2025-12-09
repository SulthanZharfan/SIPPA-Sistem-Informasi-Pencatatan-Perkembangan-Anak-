<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class GuruStudentsTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected int $defaultTableRecordsPerPage = 8;

    protected function getTableQuery(): Builder
    {
        /** @var Guru|null $guru */
        $guru = Auth::user()?->guru;

        $kelasIds = $guru?->kelas()->pluck('id') ?? collect();

        return Siswa::query()
            ->with(['kelas'])
            ->whereIn('kelas_id', $kelasIds);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('nama')
                ->label('Nama')
                ->searchable()
                ->sortable(),

            TextColumn::make('nis')
                ->label('NIS')
                ->badge()
                ->color('info')
                ->sortable(),

            TextColumn::make('kelas.nama')
                ->label('Kelas')
                ->badge()
                ->color('success'),

            TextColumn::make('kelas.tingkat')
                ->label('Tingkat')
                ->toggleable(isToggledHiddenByDefault: true)
                ->color('warning'),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableHeading(): ?string
    {
        return $this->buildHeadingText();
    }

    public function getHeading(): string
    {
        return $this->buildHeadingText();
    }

    private function buildHeadingText(): string
    {
        $user = Auth::user()?->loadMissing('guru.kelas');
        $kelasNames = $user?->guru?->kelas
            ? $user->guru->kelas->pluck('nama')->filter()->unique()->values()
            : collect();

        if ($kelasNames->count() === 1) {
            return 'List anak kelas '.$kelasNames->first();
        }

        if ($kelasNames->count() > 1) {
            return 'List anak kelas: '.$kelasNames->join(', ');
        }

        return 'List anak (belum ada kelas)';
    }
}
