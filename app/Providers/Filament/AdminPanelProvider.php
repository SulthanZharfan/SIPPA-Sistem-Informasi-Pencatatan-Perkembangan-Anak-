<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Admin\Pages\Dashboard;
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
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Override create-page action labels without separate lang files
        app('translator')->addLines([
            'filament-panels::resources/pages/create-record.form.actions.create.label' => 'Buat',
            'filament-panels::resources/pages/create-record.form.actions.create_another.label' => 'Buat dan buat baru',
            'filament-panels::resources/pages/create-record.form.actions.cancel.label' => 'Batal',
        ], app()->getLocale());

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
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
            ->homeUrl('/admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Resources/Admin/Pages'), for: 'App\Filament\Resources\Admin\Pages')
            ->pages([
                Dashboard::class,
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
                        /* Dark mode tweaks to match Guru dashboard feel */
                        @media (prefers-color-scheme: dark) {
                            .dark .fi-main {
                                background: #0f1115;
                            }
                            .dark .fi-header {
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
                            /* Override pastel inline colors on stat cards */
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
