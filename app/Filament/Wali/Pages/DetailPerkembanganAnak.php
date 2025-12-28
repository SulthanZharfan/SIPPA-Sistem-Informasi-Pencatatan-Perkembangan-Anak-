<?php

namespace App\Filament\Wali\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;

class DetailPerkembanganAnak extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Detail Perkembangan Anak';

    protected static ?string $title = 'Detail Perkembangan Anak';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected string $view = 'filament-panels::pages.page';

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Perkembangan Anak')
                ->schema([
                    Text::make('Konten dipindahkan ke Dashboard Wali untuk kemudahan akses.'),
                ]),
        ]);
    }
}
