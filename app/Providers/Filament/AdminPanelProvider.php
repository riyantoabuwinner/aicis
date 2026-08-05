<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $siteTitle = 'AICIS 2026';
        $logoUrl = null;
        $faviconUrl = null;
        try {
            $setting = \App\Models\Setting::first();
            if ($setting) {
                $siteTitle = $setting->site_title ?: 'AICIS 2026';
                $logoUrl = $setting->logo ? \Illuminate\Support\Facades\Storage::url($setting->logo) : null;
                $faviconUrl = $setting->favicon ? \Illuminate\Support\Facades\Storage::url($setting->favicon) : null;
            }
        } catch (\Exception $e) {
            // Ignore during migrations
        }

        return $panel
            ->brandName($siteTitle)
            ->favicon($faviconUrl)
            ->default()
            ->id('admin')
            ->path('admin')
            ->sidebarWidth('16rem')
            ->maxContentWidth(\Filament\Support\Enums\MaxWidth::Full)
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->registration(\App\Filament\Pages\Auth\CustomRegister::class)
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->font('Poppins')
            ->colors([
                'primary' => '#D4AF37', // Elegant Gold
                'gray' => '#063A27', // Dark Elegant Green for neutral areas (sidebar, backgrounds)
                'danger' => \Filament\Support\Colors\Color::Rose,
                'info' => \Filament\Support\Colors\Color::Blue,
                'success' => \Filament\Support\Colors\Color::Emerald,
                'warning' => \Filament\Support\Colors\Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\AnnouncementWidget::class,
            ])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                     ->label('Content Management'),
                \Filament\Navigation\NavigationGroup::make()
                     ->label('User Management'),
                \Filament\Navigation\NavigationGroup::make()
                     ->label('Site Setting'),
                \Filament\Navigation\NavigationGroup::make()
                     ->label('Profile'),
            ])
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Log Out')
                    ->url(fn () => route('admin.custom_logout'))
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->group('Profile')
                    ->sort(999),
            ])
            ->plugins([
                FilamentMenuBuilderPlugin::make()
                    ->navigationGroup('Site Setting')
                    ->navigationSort(5)
                    ->addLocations([
                        'top-menu' => 'Top Menu',
                        'main-menu' => 'Main Menu',
                        'secondary-menu' => 'Secondary Menu',
                        'footer-menu' => 'Footer Menu',
                    ])
                    ->addMenuPanels([
                        \Datlechin\FilamentMenuBuilder\MenuPanel\StaticMenuPanel::make('Default Pages')
                            ->add('Home', url('/'))
                            ->add('About', url('/about'))
                            ->add('History', url('/history'))
                            ->add('Contact', url('/contact')),
                        \Datlechin\FilamentMenuBuilder\MenuPanel\ModelMenuPanel::make()
                            ->model(\App\Models\Page::class),
                    ]),
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

    public function boot(): void
    {
        TextColumn::configureUsing(function (TextColumn $column) {
            $column->limit(50);
        });
        
        ViewAction::configureUsing(function (ViewAction $action) {
            $action->iconButton()->tooltip('View');
        });

        EditAction::configureUsing(function (EditAction $action) {
            $action->iconButton()->tooltip('Edit');
        });

        DeleteAction::configureUsing(function (DeleteAction $action) {
            $action->iconButton()->tooltip('Delete');
        });

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            fn (): \Illuminate\Contracts\View\View => \Illuminate\Support\Facades\View::make('partials.filament-lang-switcher')
        );

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::BODY_END,
            fn (): \Illuminate\Contracts\View\View => \Illuminate\Support\Facades\View::make('partials.floating-bubble')
        );

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::HEAD_END,
            fn (): string => '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">'
        );

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::STYLES_AFTER,
            fn (): string => '
                <style>
                    /* Custom background for auth pages */
                    body.fi-panel-admin:has(.fi-simple-main) {
                        background-image: url("' . asset('images/auth-bg.png') . '");
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                        background-attachment: fixed;
                    }
                    /* Improved Elegant Solid Form Container */
                    .fi-simple-main {
                        background: rgba(255, 255, 255, 0.95) !important;
                        backdrop-filter: blur(10px);
                        border-radius: 1.5rem !important;
                        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2), 0 0 40px rgba(212, 175, 55, 0.15) !important;
                        border: 1px solid rgba(212, 175, 55, 0.4) !important; 
                        padding: 1rem;
                    }
                    .dark .fi-simple-main {
                        background: rgba(4, 37, 25, 0.85) !important;
                        border: 1px solid rgba(212, 175, 55, 0.4) !important;
                        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 40px rgba(212, 175, 55, 0.1) !important;
                    }
                    
                    /* Modern Backend Styling */
                    .fi-topbar {
                        background: rgba(255, 255, 255, 0.95) !important;
                        border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
                    }
                    .dark .fi-topbar {
                        background: rgba(4, 37, 25, 0.95) !important;
                        border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
                    }
                    
                    /* Sidebar Matching Index Page with Cyber Silhouette */
                    .fi-sidebar {
                        background-color: #1b5e20 !important; /* Deep Emerald Green from index */
                        background-image: linear-gradient(rgba(27, 94, 32, 0.5), rgba(27, 94, 32, 0.6)), url("' . asset('images/auth-bg.png') . '") !important;
                        background-size: cover !important;
                        background-position: center !important;
                        border-right: 1px solid rgba(212, 175, 55, 0.4) !important;
                        box-shadow: 2px 0 15px rgba(27, 94, 32, 0.2) !important;
                    }
                    
                    /* Sidebar Text Contrast (Titles, Group Labels, Generic Text) */
                    .fi-sidebar,
                    .fi-sidebar .text-gray-500,
                    .fi-sidebar .text-gray-600,
                    .fi-sidebar .text-gray-700,
                    .fi-sidebar .text-gray-900,
                    .fi-sidebar .dark\:text-gray-400,
                    .fi-sidebar-group-label,
                    .fi-sidebar-header,
                    .fi-sidebar-header * {
                        color: #ffffff !important;
                    }
                    
                    /* Sidebar navigation links */
                    .fi-sidebar-nav-item {
                        margin-bottom: -0.1rem !important;
                    }
                    .fi-sidebar-item-button, .fi-sidebar-item-label, .fi-sidebar-item-icon {
                        color: #ffffff !important; 
                        transition: all 0.3s ease !important;
                    }
                    .fi-sidebar-item-button {
                        padding-top: 0.4rem !important;
                        padding-bottom: 0.4rem !important;
                        min-height: 2.2rem !important;
                    }
                    .fi-sidebar-item-button:hover, .fi-sidebar-item-button:hover * {
                        background-color: #2e7d32 !important; /* Lighter green from index */
                        color: #d4af37 !important; /* Metallic Gold */
                    }
                    
                    /* Active Sidebar Item */
                    .fi-sidebar-item-active > a, .fi-sidebar-item-active > a * {
                        background-color: #2e7d32 !important;
                        color: #d4af37 !important;
                    }
                    .fi-sidebar-item-active > a {
                        border-left: 4px solid #d4af37 !important;
                        border-radius: 0 !important;
                        box-shadow: inset 2px 0 8px rgba(212, 175, 55, 0.15) !important;
                    }
                    
                    /* Sidebar Header (Logo area) */
                    .fi-sidebar-header {
                        background: #17501b !important;
                        border-bottom: 1px solid rgba(212, 175, 55, 0.3) !important;
                    }
                    
                    /* Subtle Animations */
                    .fi-btn, .fi-icon-btn {
                        transition: all 0.3s ease !important;
                    }
                    .fi-btn:hover {
                        transform: translateY(-1px);
                    }
                    /* Table Striping & Hover */
                    .fi-ta-table tbody tr:nth-child(odd) td,
                    .fi-ta-table tbody tr:nth-child(odd) th {
                        background-color: rgba(27, 94, 32, 0.05) !important; /* Soft Green */
                    }
                    .fi-ta-table tbody tr:nth-child(even) td,
                    .fi-ta-table tbody tr:nth-child(even) th {
                        background-color: rgba(212, 175, 55, 0.08) !important; /* Soft Gold */
                    }
                    .dark .fi-ta-table tbody tr:nth-child(odd) td,
                    .dark .fi-ta-table tbody tr:nth-child(odd) th {
                        background-color: rgba(27, 94, 32, 0.15) !important;
                    }
                    .dark .fi-ta-table tbody tr:nth-child(even) td,
                    .dark .fi-ta-table tbody tr:nth-child(even) th {
                        background-color: rgba(212, 175, 55, 0.1) !important;
                    }
                    .fi-ta-table tbody tr:hover td,
                    .fi-ta-table tbody tr:hover th {
                        background-color: rgba(212, 175, 55, 0.18) !important;
                    }
                    .dark .fi-ta-table tbody tr:hover td,
                    .dark .fi-ta-table tbody tr:hover th {
                        background-color: rgba(212, 175, 55, 0.25) !important;
                    }
                    
                    /* Hide the entire native Filters header to remove "Filters" text, the floating loading spinner, and the text Reset link */
                    .fi-ta-filters > .flex.items-center.justify-between {
                        display: none !important;
                    }
                    
                    /* Elegant global typography scaling */
                    html {
                        font-size: 14px !important; /* Reduces overall size */
                    }
                    body {
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;
                        letter-spacing: 0.015em;
                    }
                    
                    /* Refined font weights for headings */
                    h1, h2, h3, h4, h5, h6 {
                        font-weight: 500 !important;
                        letter-spacing: 0.025em;
                    }
                </style>
            ',
        );
    }
}
