<?php

namespace App\Filament\Guru\Resources\PerkembanganKognitifs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class PerkembanganKognitifsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('siswa.nis')
                    ->label('NIS')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('siswa.kelas.nama')
                    ->label('Kelas')
                    ->badge()
                    ->color('success'),

                TextColumn::make('indikator.aspek')
                    ->label('Indikator')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('narasi')
                    ->label('Narasi')
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('status_persetujuan')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                        'menunggu' => 'Menunggu',
                        default => $state ?? '-',
                    })
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                        'danger'  => 'revisi',
                    ]),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('tanggal_input')
                    ->label('Tanggal Input')
                    ->form([
                        DatePicker::make('tanggal')
                            ->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal'] ?? null,
                            fn (Builder $q, string $tanggal) => $q->whereDate('created_at', $tanggal)
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
