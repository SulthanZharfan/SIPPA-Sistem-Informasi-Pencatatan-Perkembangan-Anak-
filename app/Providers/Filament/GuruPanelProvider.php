<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Guru\Pages\Dashboard;
use App\Filament\Guru\Widgets\GuruHeroWidget;
use App\Filament\Guru\Widgets\GuruStatsOverview;
use App\Filament\Guru\Widgets\GuruStudentsTable;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GuruPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('guru')
            ->path('guru')
            ->login()
            ->authGuard('web')
            ->homeUrl('/guru')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Guru/Resources'), for: 'App\Filament\Guru\Resources')
            ->discoverPages(in: app_path('Filament/Guru/Pages'), for: 'App\Filament\Guru\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Guru/Widgets'), for: 'App\Filament\Guru\Widgets')
            ->widgets([
                GuruStatsOverview::class,
                GuruStudentsTable::class,
            ])
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => <<<HTML
                    <style>
                        /* Dark mode refinement: keep cards legible on dark background */
                        @media (prefers-color-scheme: dark) {
                            .dark .fi-main {
                                background: #0f1115;
                            }
                            .dark .fi-widget,
                            .dark .fi-ta-ctn {
                                background: #161a20;
                                border-color: #1f252f;
                            }
                            .dark .fi-wi-stats-overview .fi-stat {
                                background: #161a20 !important;
                                border-color: #1f252f !important;
                                color: #e5e7eb;
                            }
                            /* Force override any inline pastel background on stat cards */
                            .dark .fi-wi-stats-overview [style*="background"] {
                                background: #161a20 !important;
                                border-color: #1f252f !important;
                            }
                            .dark .fi-wi-stats-overview .fi-stat .fi-stat-label {
                                color: #cbd5e1;
                            }
                            .dark .fi-wi-stats-overview .fi-stat .fi-stat-value {
                                color: #f8fafc;
                            }
                            .dark .fi-wi-stats-overview .fi-stat .fi-stat-description {
                                color: #94a3b8;
                            }
                        }
                    </style>
                HTML
            )
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
