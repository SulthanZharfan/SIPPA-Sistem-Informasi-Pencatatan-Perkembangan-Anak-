<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Wali\Pages\Dashboard;
use App\Filament\Wali\Pages\DetailPerkembanganAnak;
use App\Filament\Wali\Pages\PerkembanganFisik;
use App\Filament\Wali\Pages\PerkembanganKognitif;
use App\Filament\Wali\Pages\LaporanSemester;
use App\Filament\Wali\Pages\Presensi;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class WaliPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('wali')
            ->path('wali')
            ->login()
            ->authGuard('web')
            ->homeUrl('/wali')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Wali/Resources'), for: 'App\Filament\Wali\Resources')
            ->discoverPages(in: app_path('Filament/Wali/Pages'), for: 'App\Filament\Wali\Pages')
            ->pages([
                Dashboard::class,
                DetailPerkembanganAnak::class,
                PerkembanganFisik::class,
                PerkembanganKognitif::class,
                LaporanSemester::class,
                Presensi::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Wali/Widgets'), for: 'App\Filament\Wali\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
