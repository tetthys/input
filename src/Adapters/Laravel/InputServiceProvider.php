<?php

namespace Tetthys\Input\Adapters\Laravel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Package service provider for Tetthys\Input.
 */
final class InputServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        // For future bindings or config merge
    }

    public function boot(): void
    {
        Blade::componentNamespace(
            'Tetthys\\Input\\Adapters\\Laravel\\Components',
            'field'
        );

        // Optionally load other package resources here later:
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'field');
        // $this->loadTranslationsFrom(__DIR__.'/../lang', 'field');
    }
}
