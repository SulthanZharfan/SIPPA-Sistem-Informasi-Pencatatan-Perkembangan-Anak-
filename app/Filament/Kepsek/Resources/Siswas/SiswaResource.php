<?php

namespace App\Filament\Kepsek\Resources\Siswas;

use App\Filament\Kepsek\Resources\Siswas\Pages\ListSiswas;
use App\Filament\Kepsek\Resources\Siswas\Pages\ViewSiswa;
use App\Models\Siswa;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Filament\Infolists\Components\TextEntry;
use BackedEnum;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $modelLabel = 'Siswa';
    protected static ?string $pluralModelLabel = 'Siswa';

    // Dipakai Filament buat label record (misal di relasi)
    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        // Kepsek tidak input/edit, jadi kosongkan
        return $schema->schema([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        // Detail read-only (aman: pakai field yang pasti ada: id & nama)
        // Kalau kamu yakin kolom lain ada (mis. nisn, jk, tgl_lahir), nanti kita tambahin.
        return $schema->schema([
            TextEntry::make('id')->label('ID'),
            TextEntry::make('nama')->label('Nama'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('nama', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('kelas.nama')->label('Kelas'),
            ])
            ->defaultGroup('kelas.nama')
            ->actions([
                Actions\ViewAction::make()->label('Detail'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiswas::route('/'),
            'view'  => ViewSiswa::route('/{record}'),
        ];
    }

    // Kunci read-only (kepsek tidak CRUD)
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
