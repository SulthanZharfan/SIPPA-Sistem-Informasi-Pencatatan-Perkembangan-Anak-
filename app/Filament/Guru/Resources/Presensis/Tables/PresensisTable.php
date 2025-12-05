<?php

namespace App\Filament\Guru\Resources\Presensis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class PresensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),

                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable(),

                BadgeColumn::make('status_kehadiran')
                    ->label('Status')
                    ->colors([
                        'success' => 'hadir',
                        'info'    => 'izin',
                        'warning' => 'sakit',
                        'danger'  => 'alfa',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->label('Tanggal')
                    ->form([
                        DatePicker::make('tanggal')
                            ->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal'] ?? null,
                            fn (Builder $q, string $tanggal) => $q->whereDate('tanggal', $tanggal),
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
