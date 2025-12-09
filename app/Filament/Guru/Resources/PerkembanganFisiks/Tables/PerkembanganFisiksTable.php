<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class PerkembanganFisiksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('umur_bulan')
                    ->label('Umur (bulan)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tinggi_badan')
                    ->label('Tinggi (cm)')
                    ->sortable(),

                TextColumn::make('berat_badan')
                    ->label('Berat (kg)')
                    ->sortable(),

                TextColumn::make('lingkar_kepala')
                    ->label('Lingkar Kepala (cm)')
                    ->sortable(),

                TextColumn::make('tanggal_ukur')
                    ->label('Tanggal Ukur')
                    ->date('d-m-Y')
                    ->sortable(),

                TextColumn::make('status_persetujuan')
                    ->label('Status')
                    ->badge()
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
                Filter::make('tanggal_ukur')
                    ->label('Tanggal Ukur')
                    ->form([
                        DatePicker::make('tanggal')
                            ->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal'] ?? null,
                            fn (Builder $q, string $tanggal) => $q->whereDate('tanggal_ukur', $tanggal)
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