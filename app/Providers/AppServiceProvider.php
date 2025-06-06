<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Services\DatabaseConfigService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DatabaseConfigService::class, function ($app) {
            $config = include(storage_path('izy-starter/izy-fallback-config.php'));
            return new DatabaseConfigService($config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        $activeTheme = 'izy-helloTheme';
        view()->share('activeTheme', $activeTheme);
    }
}
