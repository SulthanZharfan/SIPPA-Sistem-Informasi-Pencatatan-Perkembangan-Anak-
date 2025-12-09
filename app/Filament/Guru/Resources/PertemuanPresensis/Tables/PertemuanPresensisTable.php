<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis\Tables;

use App\Filament\Guru\Resources\PertemuanPresensis\PertemuanPresensiResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PertemuanPresensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan ke-')
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),

                TextColumn::make('jam_mulai')
                    ->label('Mulai')
                    ->time('H:i'),

                TextColumn::make('jam_selesai')
                    ->label('Selesai')
                    ->time('H:i'),
            ])
            ->recordActions([
                Action::make('kelola')
                    ->label('Kelola Presensi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->url(fn ($record) => PertemuanPresensiResource::getUrl('kelola', ['record' => $record])),

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
