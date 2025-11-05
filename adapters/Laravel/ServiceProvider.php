<?php

namespace Tetthys\Input\Adapters\Laravel;

use Illuminate\Support\ServiceProvider as Base;
use Illuminate\Support\Facades\Blade;

final class ServiceProvider extends Base
{
    public function boot(): void
    {
        if (class_exists(Blade::class)) {
            Blade::componentNamespace('Tetthys\\Input\\Adapters\\Laravel\\Components', 'tetinput');
        }
    }
}
