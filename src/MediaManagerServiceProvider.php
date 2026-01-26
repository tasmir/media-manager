<?php

namespace Tasmir\MediaManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class MediaManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/media-manager.php', 'media-manager');

        // Add binding for the Service if needed
        $this->app->singleton(\Tasmir\MediaManager\Services\MediaService::class, function ($app) {
            return new \Tasmir\MediaManager\Services\MediaService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'media-manager');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register the Blade Component
        Blade::component('media-picker', \Tasmir\MediaManager\View\Components\MediaPicker::class);
        
        // Publish assets if needed
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/media-manager'),
            ], 'media-manager-views');

            $this->publishes([
                __DIR__ . '/../resources/assets/js/media-manager.min.js' => public_path('vendor/media-manager/js/media-manager.min.js'),
                __DIR__ . '/../resources/assets/css/media-manager.css' => public_path('vendor/media-manager/css/media-manager.css'),
            ], 'media-manager-assets');

            $this->publishes([
                __DIR__ . '/../config/media-manager.php' => config_path('media-manager.php'),
            ], 'media-manager-config');
        }
    }
}
