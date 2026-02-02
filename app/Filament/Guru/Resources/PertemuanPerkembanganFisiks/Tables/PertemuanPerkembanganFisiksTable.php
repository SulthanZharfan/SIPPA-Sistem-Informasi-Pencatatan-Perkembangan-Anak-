<?php

namespace App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Tables;

use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PertemuanPerkembanganFisiksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount([
                'perkembanganFisiks as pf_normal_count' => fn ($q) => $q->where('status_ringkas', 'normal'),
                'perkembanganFisiks as pf_perlu_perhatian_count' => fn ($q) => $q->where('status_ringkas', 'perlu_perhatian'),
                'perkembanganFisiks as pf_terisi_count' => fn ($q) => $q
                    ->whereNotNull('tinggi_badan')
                    ->whereNotNull('berat_badan'),
            ]))
            ->searchPlaceholder('Cari kelas / tahun ajaran / pertemuan')
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tahunAjaran.tahun')
                    ->label('Tahun Ajaran')
                    ->formatStateUsing(fn ($state, $record) => $record?->tahunAjaran?->label ?? $state)
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan Ke-')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Mulai')
                    ->time('H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('jam_selesai')
                    ->label('Selesai')
                    ->time('H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending'  => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default    => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                    ])
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ringkasan')
                    ->label('Ringkasan')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $normal = (int) ($record->pf_normal_count ?? 0);
                        $perluPerhatian = (int) ($record->pf_perlu_perhatian_count ?? 0);
                        $terisi = (int) ($record->pf_terisi_count ?? 0);

                        if ($terisi === 0) {
                            return 'Belum ada data';
                        }

                        return "Normal: {$normal} | Perlu Perhatian: {$perluPerhatian} | Terisi: {$terisi}";
                    })
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),

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
                    ->label('Kelola Perkembangan')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->url(fn ($record) => PertemuanPerkembanganFisikResource::getUrl('kelola', ['record' => $record])),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
