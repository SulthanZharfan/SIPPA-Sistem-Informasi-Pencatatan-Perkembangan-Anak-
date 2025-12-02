<?php

namespace App\Filament\Resources\Walis;

use App\Filament\Resources\Walis\Pages\CreateWali;
use App\Filament\Resources\Walis\Pages\EditWali;
use App\Filament\Resources\Walis\Pages\ListWalis;
use App\Filament\Resources\Walis\Schemas\WaliForm;
use App\Filament\Resources\Walis\Tables\WalisTable;
use App\Models\Wali;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WaliResource extends Resource
{
    protected static ?string $model = Wali::class;

    // icon di sidebar
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUsers;

    // grup menu di sidebar
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Wali Murid';
    protected static ?string $pluralModelLabel = 'Wali Murid';
    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return WaliForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WalisTable::configure($table);
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
            'index'  => ListWalis::route('/'),
            'create' => CreateWali::route('/create'),
            'edit'   => EditWali::route('/{record}/edit'),
        ];
    }
}
