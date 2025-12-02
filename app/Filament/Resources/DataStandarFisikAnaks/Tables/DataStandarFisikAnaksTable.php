<?php

namespace App\Filament\Resources\DataStandarFisikAnaks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DataStandarFisikAnaksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('umur_bulan')
                    ->label('Umur (bulan)')
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),

                TextColumn::make('tb_min')
                    ->label('TB Min'),

                TextColumn::make('tb_max')
                    ->label('TB Max'),

                TextColumn::make('bb_min')
                    ->label('BB Min'),

                TextColumn::make('bb_max')
                    ->label('BB Max'),

                TextColumn::make('lk_min')
                    ->label('LK Min')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('lk_max')
                    ->label('LK Max')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('umur_bulan')
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
