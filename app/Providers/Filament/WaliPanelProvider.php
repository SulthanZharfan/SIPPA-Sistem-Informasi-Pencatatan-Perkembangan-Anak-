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
use Filament\Actions\Action;
use Filament\Facades\Filament;
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

class WaliPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('wali')
            ->path('wali')
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
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => <<<HTML
                    <style>
                        .wali-welcome-stat {
                            background: linear-gradient(135deg, #fff7d6 0%, #e6f7ff 100%) !important;
                            border-color: #f2e8c9 !important;
                        }
                        .wali-welcome-stat .fi-stat-label,
                        .wali-welcome-stat .fi-stat-value,
                        .wali-welcome-stat .fi-stat-description {
                            color: #1f2937 !important;
                        }
                        @media (prefers-color-scheme: dark) {
                            .dark .wali-welcome-stat {
                                background: #161a20 !important;
                                border-color: #1f252f !important;
                            }
                            .dark .wali-welcome-stat .fi-stat-label {
                                color: #cbd5e1 !important;
                            }
                            .dark .wali-welcome-stat .fi-stat-value {
                                color: #f8fafc !important;
                            }
                            .dark .wali-welcome-stat .fi-stat-description {
                                color: #fbbf24 !important;
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
