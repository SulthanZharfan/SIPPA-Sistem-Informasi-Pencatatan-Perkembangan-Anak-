<?php

namespace App\Providers\Filament;

use App\Filament\Kepsek\Pages\Dashboard;
use App\Filament\Kepsek\Widgets\KepsekStatsOverview;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class KepsekPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('kepsek')
            ->path('kepsek')
            ->login()
            ->authGuard('web')
            ->userMenuItems([
                'logout' => fn (Action $action): Action => $action
                    ->label('Logout')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Keluar')
                    ->modalDescription('Apakah Anda yakin ingin keluar dari akun ini?')
                    ->modalSubmitActionLabel('Ya, Keluar')
                    ->modalCancelActionLabel('Batal')
                    ->url(null)
                    ->postToUrl(false)
                    ->action(function () {
                        Filament::auth()->logout();
                        request()->session()->invalidate();
                        request()->session()->regenerateToken();

                        return redirect()->to(Filament::getLoginUrl() ?? '/');
                    }),
            ])
            ->homeUrl('/kepsek')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Kepsek/Resources'), for: 'App\Filament\Kepsek\Resources')
            ->discoverPages(in: app_path('Filament/Kepsek/Pages'), for: 'App\Filament\Kepsek\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Kepsek/Widgets'), for: 'App\Filament\Kepsek\Widgets')
            ->widgets([
                KepsekStatsOverview::class,
            ])
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
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => <<<HTML
                    <style>
                        @media (prefers-color-scheme: dark) {
                            .dark .fi-wi-stats-overview .fi-stat {
                                background: #161a20 !important;
                                border-color: #1f252f !important;
                                color: #e5e7eb;
                            }
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
                            .dark .fi-tabs .fi-tabs-item,
                            .dark .fi-tabs .fi-tabs-item .fi-tabs-item-label,
                            .dark .fi-tabs .fi-tabs-item.fi-tabs-item-active,
                            .dark .fi-tabs .fi-tabs-item.fi-tabs-item-active .fi-tabs-item-label {
                                color: #f8fafc;
                            }
                        }
                    </style>
                HTML
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
