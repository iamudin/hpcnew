<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Account;
use App\Filament\Pages\ProfileMahasiswa;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('login-bg', asset('login.css')),
        ]);
        FilamentView::registerRenderHook(
    PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
    fn () => view('login.script')
);

FilamentView::registerRenderHook(
    PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
    fn () => view('login-error')
);
    }
    public function panel(Panel $panel): Panel
    {
        return $panel
        ->brandName('SIPL')
        ->brandLogoHeight(fn()=>request()->routeIs('filament.admin.auth.login') ? '112px':'40px')
        ->brandLogo(asset('light.png'))
        ->darkModeBrandLogo(asset('dark.png'))
        ->authMiddleware([
            \App\Http\Middleware\CheckUserStatus::class
        ])
         ->sidebarCollapsibleOnDesktop()
            ->userMenuItems([
                    Action::make('Lihat Website')
                ->url('/')
                ->openUrlInNewTab()
                ->icon('heroicon-o-globe-alt'),
          Action::make('Akun')
                ->url(fn() => ProfileMahasiswa::getUrl())
                ->icon('heroicon-o-user')->visible(fn() => auth()->user()->isMahasiswa()),
            Action::make('Akun')
                ->url(fn() => Account::getUrl())
                ->icon('heroicon-o-user'),
                ])
            ->default()
            ->id('admin')
            ->spa()
            ->path('auth')
            ->maxContentWidth('full')
            ->renderHook(
            'panels::auth.login.form.after',   // letakkan setelah form login
            fn () => view('auth.socialite.google')
        )
            ->login()
          
            ->colors([
                'primary' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->globalSearch(false)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
