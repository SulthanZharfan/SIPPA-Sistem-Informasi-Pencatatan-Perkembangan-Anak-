<?php

namespace App\Filament\Resources\TahunAjarans;

use App\Filament\Resources\TahunAjarans\Pages\CreateTahunAjaran;
use App\Filament\Resources\TahunAjarans\Pages\EditTahunAjaran;
use App\Filament\Resources\TahunAjarans\Pages\ListTahunAjarans;
use App\Filament\Resources\TahunAjarans\Schemas\TahunAjaranForm;
use App\Filament\Resources\TahunAjarans\Tables\TahunAjaransTable;
use App\Models\TahunAjaran;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;

    // Icon sidebar
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    // Judul record (dipakai di breadcrumbs, dll)
    protected static ?string $recordTitleAttribute = 'tahun';

    // Label & grup di sidebar
    protected static ?string $navigationLabel = 'Tahun Ajaran';
    protected static ?string $pluralModelLabel = 'Tahun Ajaran';
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return TahunAjaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TahunAjaransTable::configure($table);
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
            'index'  => ListTahunAjarans::route('/'),
            'create' => CreateTahunAjaran::route('/create'),
            'edit'   => EditTahunAjaran::route('/{record}/edit'),
        ];
    }
}
