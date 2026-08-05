<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\Filament\Http\Responses\Auth\Contracts\LogoutResponse::class, \App\Filament\Responses\LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $setting = \App\Models\Setting::first();
                if ($setting && !empty($setting->smtp_host)) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $setting->smtp_host,
                        'mail.mailers.smtp.port' => $setting->smtp_port,
                        'mail.mailers.smtp.encryption' => $setting->smtp_encryption,
                        'mail.mailers.smtp.username' => $setting->smtp_username,
                        'mail.mailers.smtp.password' => $setting->smtp_password,
                        'mail.from.address' => $setting->mail_from_address ?? $setting->smtp_username,
                        'mail.from.name' => $setting->mail_from_name ?? config('app.name'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently ignore during initial migrations
        }

        \App\Models\PaperSubmission::observe(\App\Observers\PaperSubmissionObserver::class);
        \Illuminate\Support\Facades\Gate::policy(\Datlechin\FilamentMenuBuilder\Models\Menu::class, \App\Policies\MenuPolicy::class);
    }
}
