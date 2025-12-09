<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis\Tables;

use App\Filament\Guru\Resources\PertemuanPresensis\PertemuanPresensiResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PertemuanPresensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Cari kelas / pertemuan / tanggal')
            ->columns([
                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan ke-')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('jam_mulai')
                    ->label('Mulai')
                    ->time('H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('jam_selesai')
                    ->label('Selesai')
                    ->time('H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama'),

                Filter::make('tanggal')
                    ->label('Tanggal')
                    ->form([
                        DatePicker::make('mulai')->label('Dari'),
                        DatePicker::make('sampai')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['mulai'] ?? null, fn ($q, $date) => $q->whereDate('tanggal', '>=', $date))
                            ->when($data['sampai'] ?? null, fn ($q, $date) => $q->whereDate('tanggal', '<=', $date));
                    }),
            ])
            ->recordActions([
                Action::make('kelola')
                    ->label('Kelola Presensi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->url(fn ($record) => PertemuanPresensiResource::getUrl('kelola', ['record' => $record])),

                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus'),
                ]),
            ]);
    }
}
