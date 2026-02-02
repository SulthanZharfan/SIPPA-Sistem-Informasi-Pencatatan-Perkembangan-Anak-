<?php

namespace App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks;

use App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\Pages\ListPertemuanPerkembanganFisiks;
use App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\Pages\ViewPertemuanPerkembanganFisik;
use App\Filament\Kepsek\Resources\PertemuanPerkembanganFisiks\RelationManagers\PerkembanganFisiksRelationManager;
use App\Models\PertemuanPerkembanganFisik;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\PaginationMode;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;

class PertemuanPerkembanganFisikResource extends Resource
{
    protected static ?string $model = PertemuanPerkembanganFisik::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Perkembangan Fisik';
    protected static ?string $modelLabel = 'Pertemuan Perkembangan Fisik';
    protected static ?string $pluralModelLabel = 'Perkembangan Fisik';
    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';

    protected static ?string $recordTitleAttribute = 'pertemuan_ke';

    public static function form(Schema $schema): Schema
    {
        // Kepsek tidak input/edit, jadi tidak perlu form
        return $schema->schema([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Pertemuan')
                ->schema([
                    TextEntry::make('tanggal')
                        ->label('Tanggal')
                        ->date('d M Y'),

                    TextEntry::make('kelas.nama')
                        ->label('Kelas'),

                    TextEntry::make('guru.nama')
                        ->label('Guru'),

                    TextEntry::make('pertemuan_ke')
                        ->label('Pertemuan'),

                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn (?string $state) => match ($state) {
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'pending' => 'Menunggu',
                            default => $state ?? '-',
                        })
                        ->colors([
                            'warning' => 'pending',
                            'success' => 'approved',
                            'danger' => 'rejected',
                        ]),

                    TextEntry::make('approved_at')
                        ->label('Disetujui pada')
                        ->dateTime('d M Y H:i')
                        ->placeholder('-'),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50])
            ->extremePaginationLinks()
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('guru.nama')
                    ->label('Guru')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('terisi_count')
                    ->label('Terisi')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('normal_count')
                    ->label('Normal')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('perlu_perhatian_count')
                    ->label('Perlu Perhatian')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'pending' => 'Menunggu',
                        default => $state ?? '-',
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Disetujui pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->label('Detail')
                    ->url(fn (PertemuanPerkembanganFisik $record): string => static::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['kelas', 'guru'])
            ->withCount([
                'perkembanganFisiks as terisi_count',
                'perkembanganFisiks as normal_count' => fn (Builder $q) => $q->where('status_ringkas', 'normal'),
                'perkembanganFisiks as perlu_perhatian_count' => fn (Builder $q) => $q->where('status_ringkas', 'perlu_perhatian'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PerkembanganFisiksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        // sengaja cuma index + view (tanpa create/edit)
        return [
            'index' => ListPertemuanPerkembanganFisiks::route('/'),
            'view' => ViewPertemuanPerkembanganFisik::route('/{record}'),
        ];
    }

    // kunci read-only
    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
}
