<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis;

use App\Filament\Guru\Resources\PertemuanPresensis\Pages;
use App\Filament\Guru\Resources\PertemuanPresensis\Schemas\PertemuanPresensiForm;
use App\Filament\Guru\Resources\PertemuanPresensis\Tables\PertemuanPresensisTable;
use App\Models\Guru;
use App\Models\PertemuanPresensi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PertemuanPresensiResource extends Resource
{
    protected static ?string $model = PertemuanPresensi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Pencatatan';
    protected static ?string $navigationLabel = 'Presensi';
    protected static ?string $pluralModelLabel = 'Presensi';
    protected static ?string $slug = 'pertemuan-presensis';

    /**
     * Guru hanya boleh melihat pertemuan yang dia buat sendiri.
     */
    public static function getEloquentQuery(): Builder
    {
        $currentGuruId = Guru::where('user_id', Auth::id())->value('id');

        return parent::getEloquentQuery()
            ->where('guru_id', $currentGuruId);
    }

    public static function form(Schema $schema): Schema
    {
        return PertemuanPresensiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PertemuanPresensisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPertemuanPresensis::route('/'),
            'create' => Pages\CreatePertemuanPresensi::route('/create'),
            'edit'   => Pages\EditPertemuanPresensi::route('/{record}/edit'),
            'kelola' => Pages\KelolaPresensi::route('/{record}/kelola'),
        ];
    }
}
