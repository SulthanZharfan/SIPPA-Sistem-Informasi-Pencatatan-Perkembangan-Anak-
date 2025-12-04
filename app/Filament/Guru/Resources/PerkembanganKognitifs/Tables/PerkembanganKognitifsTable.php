<?php

namespace App\Filament\Guru\Resources\PerkembanganKognitifs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
