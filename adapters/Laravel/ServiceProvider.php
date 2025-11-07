<?php

namespace Tetthys\Input\Adapters\Laravel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Package service provider for Tetthys\Input.
 *
 * This provider is auto-discovered via composer.json:
 * {
 *   "extra": {
 *     "laravel": {
 *       "providers": [
 *         "Tetthys\\Input\\Adapters\\Laravel\\ServiceProvider"
 *       ]
 *     }
 *   }
 * }
 */
final class ServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Use this method to bind container services or merge config if needed.
     * Kept empty because this package currently doesn't expose bindings.
     */
    public function register(): void
    {
        // $this->mergeConfigFrom(__DIR__.'/../config/input.php', 'tetinput'); // (optional)
    }

    /**
     * Bootstrap package services.
     *
     * We register the Blade component namespace so users can use:
     *   <x-field:text ... />
     *   <x-field:select ... />
     *   <x-field:checkbox-group ... />
     *   <x-field:select-advanced ... />
     */
    public function boot(): void
    {
        Blade::componentNamespace(
            'Tetthys\\Input\\Adapters\\Laravel\\Components',
            'field'
        );

        // If you later add package views / translations / routes, enable as needed:
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'field');         // (optional)
        // $this->loadTranslationsFrom(__DIR__.'/../lang', 'field');             // (optional)
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');                  // (optional)
        // if ($this->app->runningInConsole()) {
        //     $this->publishes([__DIR__.'/../config/input.php' => config_path('input.php')], 'tetinput-config');
        // }
    }
}
