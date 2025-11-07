<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Contracts\ValueProvider;

final class LaravelRequestValueProvider implements ValueProvider
{
    public function has(string $name): bool
    {
        if (!function_exists('request')) return false;
        return request()->has($name);
    }

    public function get(string $name, mixed $default = null): mixed
    {
        if (!function_exists('request')) return $default;
        return request()->input($name, $default);
    }
}
