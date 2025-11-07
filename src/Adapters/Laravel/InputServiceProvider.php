<?php

namespace Tetthys\Input\Adapters\Laravel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class InputServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        // Reserved for bindings or config merging
    }

    public function boot(): void
    {
        // Register Blade components under <x-field::...>
        Blade::componentNamespace(
            'Tetthys\\Input\\Adapters\\Laravel\\Components',
            'field'
        );
    }
}
