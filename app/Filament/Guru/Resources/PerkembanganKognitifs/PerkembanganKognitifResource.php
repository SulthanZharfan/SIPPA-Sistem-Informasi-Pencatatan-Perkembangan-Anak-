<?php

namespace App\Filament\Guru\Resources\PerkembanganKognitifs;

use App\Filament\Guru\Resources\PerkembanganKognitifs\Pages\CreatePerkembanganKognitif;
use App\Filament\Guru\Resources\PerkembanganKognitifs\Pages\EditPerkembanganKognitif;
use App\Filament\Guru\Resources\PerkembanganKognitifs\Pages\ListPerkembanganKognitifs;
use App\Filament\Guru\Resources\PerkembanganKognitifs\Schemas\PerkembanganKognitifForm;
use App\Filament\Guru\Resources\PerkembanganKognitifs\Tables\PerkembanganKognitifsTable;
use App\Models\PerkembanganKognitif;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PerkembanganKognitifResource extends Resource
{
    protected static ?string $model = PerkembanganKognitif::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string | UnitEnum | null $navigationGroup = 'Pencatatan';
    protected static ?string $navigationLabel = 'Perkembangan Kognitif';
    protected static ?string $pluralModelLabel = 'Perkembangan Kognitif';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PerkembanganKognitifForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerkembanganKognitifsTable::configure($table);
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
            'index'  => ListPerkembanganKognitifs::route('/'),
            'create' => CreatePerkembanganKognitif::route('/create'),
            'edit'   => EditPerkembanganKognitif::route('/{record}/edit'),
        ];
    }
}
