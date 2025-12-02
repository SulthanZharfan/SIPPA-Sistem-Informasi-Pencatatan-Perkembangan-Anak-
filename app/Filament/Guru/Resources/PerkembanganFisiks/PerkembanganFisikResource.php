<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks;

use App\Filament\Guru\Resources\PerkembanganFisiks\Pages\CreatePerkembanganFisik;
use App\Filament\Guru\Resources\PerkembanganFisiks\Pages\EditPerkembanganFisik;
use App\Filament\Guru\Resources\PerkembanganFisiks\Pages\ListPerkembanganFisiks;
use App\Filament\Guru\Resources\PerkembanganFisiks\Schemas\PerkembanganFisikForm;
use App\Filament\Guru\Resources\PerkembanganFisiks\Tables\PerkembanganFisiksTable;
use App\Models\PerkembanganFisik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PerkembanganFisikResource extends Resource
{
    protected static ?string $model = PerkembanganFisik::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PerkembanganFisikForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerkembanganFisiksTable::configure($table);
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
            'index' => ListPerkembanganFisiks::route('/'),
            'create' => CreatePerkembanganFisik::route('/create'),
            'edit' => EditPerkembanganFisik::route('/{record}/edit'),
        ];
    }
}
