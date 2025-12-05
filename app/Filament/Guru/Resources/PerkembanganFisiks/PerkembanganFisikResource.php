<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks;

use App\Filament\Guru\Resources\PerkembanganFisiks\Pages\CreatePerkembanganFisik;
use App\Filament\Guru\Resources\PerkembanganFisiks\Pages\EditPerkembanganFisik;
use App\Filament\Guru\Resources\PerkembanganFisiks\Pages\ListPerkembanganFisiks;
use App\Filament\Guru\Resources\PerkembanganFisiks\Schemas\PerkembanganFisikForm;
use App\Filament\Guru\Resources\PerkembanganFisiks\Tables\PerkembanganFisiksTable;
use App\Models\PerkembanganFisik;
use App\Models\TahunAjaran;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PerkembanganFisikResource extends Resource
{
    protected static ?string $model = PerkembanganFisik::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string | UnitEnum | null $navigationGroup = 'Pencatatan';
    protected static ?string $navigationLabel = 'Perkembangan Fisik';
    protected static ?string $pluralModelLabel = 'Perkembangan Fisik';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PerkembanganFisikForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerkembanganFisiksTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $guruId = Auth::user()?->guru?->id ?? 0;

        return parent::getEloquentQuery()
            ->whereHas('siswa.kelas', fn (Builder $query) => $query->where('guru_id', $guruId));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPerkembanganFisiks::route('/'),
            'create' => CreatePerkembanganFisik::route('/create'),
            'edit'   => EditPerkembanganFisik::route('/{record}/edit'),
        ];
    }
}
