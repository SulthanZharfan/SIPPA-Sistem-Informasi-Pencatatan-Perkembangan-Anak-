<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PerkembanganFisiksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('guru_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tahun_ajaran_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('standar_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tinggi_badan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('berat_badan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('lingkar_kepala')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_ukur')
                    ->date()
                    ->sortable(),
                TextColumn::make('umur_bulan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('foto')
                    ->searchable(),
                TextColumn::make('kategori_tb')
                    ->searchable(),
                TextColumn::make('kategori_bb')
                    ->searchable(),
                TextColumn::make('kategori_lk')
                    ->searchable(),
                TextColumn::make('status_persetujuan')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
