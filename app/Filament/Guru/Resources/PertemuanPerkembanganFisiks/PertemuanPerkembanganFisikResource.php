<?php

namespace App\Filament\Guru\Resources\PertemuanPerkembanganFisiks;

use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Pages\CreatePertemuanPerkembanganFisik;
use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Pages\EditPertemuanPerkembanganFisik;
use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Pages\KelolaPerkembanganFisik;
use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Pages\ListPertemuanPerkembanganFisiks;
use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Schemas\PertemuanPerkembanganFisikForm;
use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Tables\PertemuanPerkembanganFisiksTable;
use App\Models\Guru;
use App\Models\PertemuanPerkembanganFisik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PertemuanPerkembanganFisikResource extends Resource
{
    protected static ?string $model = PertemuanPerkembanganFisik::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Pencatatan';

    protected static ?string $navigationLabel = 'Perkembangan Fisik';

    protected static ?string $pluralModelLabel = 'Perkembangan Fisik';

    protected static ?string $modelLabel = 'Perkembangan Fisik';

    protected static ?string $recordTitleAttribute = 'pertemuan_ke';

    public static function form(Schema $schema): Schema
    {
        return PertemuanPerkembanganFisikForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PertemuanPerkembanganFisiksTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $currentGuruId = Guru::where('user_id', Auth::id())->value('id');

        return parent::getEloquentQuery()
            ->where('guru_id', $currentGuruId);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPertemuanPerkembanganFisiks::route('/'),
            'create' => CreatePertemuanPerkembanganFisik::route('/create'),
            'edit'   => EditPertemuanPerkembanganFisik::route('/{record}/edit'),
            'kelola' => KelolaPerkembanganFisik::route('/{record}/kelola'),
        ];
    }
}
