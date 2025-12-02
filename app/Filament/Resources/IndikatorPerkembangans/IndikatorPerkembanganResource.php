<?php

namespace App\Filament\Resources\IndikatorPerkembangans;

use App\Filament\Resources\IndikatorPerkembangans\Pages\CreateIndikatorPerkembangan;
use App\Filament\Resources\IndikatorPerkembangans\Pages\EditIndikatorPerkembangan;
use App\Filament\Resources\IndikatorPerkembangans\Pages\ListIndikatorPerkembangans;
use App\Filament\Resources\IndikatorPerkembangans\Schemas\IndikatorPerkembanganForm;
use App\Filament\Resources\IndikatorPerkembangans\Tables\IndikatorPerkembangansTable;
use App\Models\IndikatorPerkembangan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IndikatorPerkembanganResource extends Resource
{
    protected static ?string $model = IndikatorPerkembangan::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Indikator Perkembangan';
    protected static ?string $pluralModelLabel = 'Indikator Perkembangan';
    protected static ?string $recordTitleAttribute = 'aspek';

    public static function form(Schema $schema): Schema
    {
        return IndikatorPerkembanganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IndikatorPerkembangansTable::configure($table);
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
            'index'  => ListIndikatorPerkembangans::route('/'),
            'create' => CreateIndikatorPerkembangan::route('/create'),
            'edit'   => EditIndikatorPerkembangan::route('/{record}/edit'),
        ];
    }
}
