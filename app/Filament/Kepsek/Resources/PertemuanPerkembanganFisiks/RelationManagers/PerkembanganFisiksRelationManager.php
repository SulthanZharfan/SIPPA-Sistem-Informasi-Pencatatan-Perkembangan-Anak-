<?php

namespace App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PerkembanganFisiksRelationManager extends RelationManager
{
    protected static string $relationship = 'perkembanganFisiks';

    protected static ?string $title = 'Detail Siswa';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('siswa.nama')
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tinggi_badan')
                    ->label('TB')
                    ->sortable(),

                Tables\Columns\TextColumn::make('berat_badan')
                    ->label('BB')
                    ->sortable(),

                Tables\Columns\TextColumn::make('lingkar_kepala')
                    ->label('LK')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori_tb')
                    ->label('Kategori TB'),

                Tables\Columns\TextColumn::make('kategori_bb')
                    ->label('Kategori BB'),

                Tables\Columns\TextColumn::make('kategori_lk')
                    ->label('Kategori LK'),

                Tables\Columns\BadgeColumn::make('status_ringkas')
                    ->label('Status Ringkas')
                    ->colors([
                        'success' => 'normal',
                        'warning' => 'perlu_perhatian',
                    ]),

                Tables\Columns\BadgeColumn::make('status_persetujuan')
                    ->label('Status Persetujuan')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                        'danger' => 'revisi',
                    ]),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
