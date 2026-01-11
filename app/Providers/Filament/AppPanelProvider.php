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
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

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
            ->renderHook(
                'panels::auth.login.form.before',
                fn (): View => view('filament.google-login-button')
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('images/Unpad_logo.png'))
            ->brandLogoHeight('2.2rem')
            ->viteTheme('resources/css/filament/app/theme.css')
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): string => '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">',
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Asset')
                     ->icon('heroicon-s-squares-2x2'),


                NavigationGroup::make()
                    ->label('Teknik')
                     ->icon('heroicon-s-wrench-screwdriver'),


                 NavigationGroup::make()
                    ->label('Urbang')
                     ->icon('heroicon-s-building-library'),

                NavigationGroup::make()
                    ->label('Infrastruktur')
                     ->icon('heroicon-s-building-storefront'),

                NavigationGroup::make()
                    ->label('Logistik')
                     ->icon('heroicon-s-pencil'),

                NavigationGroup::make()
                    ->label('Poll Kendaraan')
                     ->icon('heroicon-s-truck'),

                NavigationGroup::make()
                    ->label('Pelaporan')
                     ->icon('heroicon-s-document'),


                NavigationGroup::make()
                    ->label('Roles and Permissions')
                    ->icon('heroicon-s-lock-closed')



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
            ])
             ->plugin(FilamentSpatieRolesPermissionsPlugin::make());


    }
 }
