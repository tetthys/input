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
        $this->app->singleton(ErrorStore::class, fn() => new LaravelErrorStore());
        $this->app->singleton(ErrorStyler::class, fn() => new DefaultErrorStyler('error'));

        $this->app->singleton('tetinput.provider.old', fn() => LaravelOldInputProvider::make());
        $this->app->singleton('tetinput.provider.request', fn() => new LaravelRequestValueProvider());

        $this->app->tag(
            ['tetinput.provider.old', 'tetinput.provider.request'],
            'tetinput.value_providers'
        );
    }

    public function boot(): void
    {
        Blade::componentNamespace('Tetthys\\Input\\Adapters\\Laravel\\Components', 'field');
    }
}
