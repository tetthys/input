<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Contracts\ValueProvider;

final class LaravelRequestValueProvider implements ValueProvider
{
    public function has(string $name): bool
    {
        if (!function_exists('request')) return false;

        // Check both direct key and request()->has()
        $all = request()->all();
        return array_key_exists($name, $all) || request()->has($name);
    }

    public function get(string $name, mixed $default = null): mixed
    {
        if (!function_exists('request')) return $default;

        // Read value directly from full input array first
        $all = request()->all();
        if (array_key_exists($name, $all)) {
            return $all[$name];
        }
        return request()->input($name, $default);
    }
}
