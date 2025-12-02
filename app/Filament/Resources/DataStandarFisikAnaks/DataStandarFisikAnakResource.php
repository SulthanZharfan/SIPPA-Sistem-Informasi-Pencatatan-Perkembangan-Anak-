<?php

namespace App\Filament\Resources\DataStandarFisikAnaks;

use App\Filament\Resources\DataStandarFisikAnaks\Pages\CreateDataStandarFisikAnak;
use App\Filament\Resources\DataStandarFisikAnaks\Pages\EditDataStandarFisikAnak;
use App\Filament\Resources\DataStandarFisikAnaks\Pages\ListDataStandarFisikAnaks;
use App\Filament\Resources\DataStandarFisikAnaks\Schemas\DataStandarFisikAnakForm;
use App\Filament\Resources\DataStandarFisikAnaks\Tables\DataStandarFisikAnaksTable;
use App\Models\DataStandarFisik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DataStandarFisikAnakResource extends Resource
{
    protected static ?string $model = DataStandarFisik::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Standar Fisik Anak';
    protected static ?string $pluralModelLabel = 'Standar Fisik Anak';
    protected static ?string $recordTitleAttribute = 'umur_bulan';

    public static function form(Schema $schema): Schema
    {
        return DataStandarFisikAnakForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataStandarFisikAnaksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDataStandarFisikAnaks::route('/'),
            'create' => CreateDataStandarFisikAnak::route('/create'),
            'edit'   => EditDataStandarFisikAnak::route('/{record}/edit'),
        ];
    }
}
