<?php

namespace App\Filament\Guru\Resources\Presensis;

use App\Filament\Guru\Resources\Presensis\Pages;
use App\Filament\Guru\Resources\Presensis\Schemas\PresensiForm;
use App\Filament\Guru\Resources\Presensis\Tables\PresensisTable;
use App\Models\Guru;
use App\Models\Presensi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Pencatatan';
    protected static ?string $navigationLabel = 'Presensi';
    protected static ?string $pluralModelLabel = 'Presensi';
    protected static ?string $slug = 'presensis';
    protected static bool $shouldRegisterNavigation = false;

    /**
     * Guru hanya boleh melihat presensi miliknya sendiri.
     */
    public static function getEloquentQuery(): Builder
    {
        $currentGuruId = Guru::where('user_id', Auth::id())->value('id');

        return parent::getEloquentQuery()
            ->where('guru_id', $currentGuruId);
    }

    public static function form(Schema $schema): Schema
    {
        return PresensiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresensisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPresensis::route('/'),
            'create' => Pages\CreatePresensi::route('/create'),
            'edit'   => Pages\EditPresensi::route('/{record}/edit'),
        ];
    }
}
