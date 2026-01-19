<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationGroup;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Filament\Pages\Profile;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->homeUrl('/app/halaman-utama')
            ->login()
            ->plugins([
                FilamentShieldPlugin::make()
            ])
            ->renderHook(
                'panels::auth.login.form.before',
                fn (): View => view('filament.google-login-button')
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->favicon(asset('images/Favicon_Unpad.ico'))
            ->brandLogo(asset('images/Unpad_logo.png'))
            ->brandLogoHeight('2.2rem')
            ->viteTheme('resources/css/filament/app/theme.css')
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): string => '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">' .
                                 '<link rel="manifest" href="/manifest.json">',
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Profile::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => '<script>if ("serviceWorker" in navigator) {navigator.serviceWorker.register("/serviceworker.js").then(registration => {console.log("Service Worker registered with scope:", registration.scope);}).catch(error => {console.error("Service Worker registration failed:", error);});}</script>',
            )
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->userMenuItems([
                \Filament\Navigation\MenuItem::make()
                    ->label('Profil')
                    ->url(fn (): string => \App\Filament\Pages\Profile::getUrl())
                    ->icon('heroicon-o-user'),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([


                NavigationGroup::make()
                    ->label('Poll Kendaraan')
                     ->icon('heroicon-s-truck'),

                NavigationGroup::make()
                    ->label('Pelaporan')
                     ->icon('heroicon-s-document'),

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
            ->authMiddleware([
                Authenticate::class,
            ]);


    }
 }
