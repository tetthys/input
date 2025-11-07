<?php

namespace Tetthys\Input\Adapters\Laravel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Contracts\ErrorStyler;
use Tetthys\Input\Support\DefaultErrorStyler;

final class InputServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        // Default ErrorStore
        $this->app->singleton(ErrorStore::class, fn() => new LaravelErrorStore());

        // Default ErrorStyler (BEM token "error" → {block}--error)
        $this->app->singleton(ErrorStyler::class, fn() => new DefaultErrorStyler('error'));

        // Value providers
        $this->app->bind('tetinput.provider.old', fn() => LaravelOldInputProvider::make());
        $this->app->singleton('tetinput.provider.request', fn() => new LaravelRequestValueProvider());

        // Tag in order (old > request). Apps can retag in custom providers.
        $this->app->tag(
            ['tetinput.provider.old', 'tetinput.provider.request'],
            'tetinput.value_providers'
        );
    }

    public function boot(): void
    {
        // <x-field::...>
        Blade::componentNamespace('Tetthys\\Input\\Adapters\\Laravel\\Components', 'field');
    }
}
