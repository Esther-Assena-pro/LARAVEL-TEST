<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class EstherPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('esther')
            ->path('esther')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Esther/Resources'), for: 'App\\Filament\\Esther\\Resources')
            ->discoverPages(in: app_path('Filament/Esther/Pages'), for: 'App\\Filament\\Esther\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Esther/Widgets'), for: 'App\\Filament\\Esther\\Widgets')
            ->widgets([]) // Assure que FilamentInfoWidget est exclu
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
            ->renderHook('panels::global-search.before', fn () => '')
            ->renderHook('panels::topbar.end', fn () => '') // Supprime le bloc à droite
            ->renderHook('panels::footer', fn () => ''); // Assure que le footer est vide
    }
}