<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Contracts\ValueProvider;

final class LaravelRequestValueProvider implements ValueProvider
{
    public function has(string $name): bool
    {
        if (!function_exists('request')) return false;
        $val = request()->input($name);
        return ($val !== null && trim((string) $val) !== '');
    }

    public function get(string $name, mixed $default = null): mixed
    {
        if (!function_exists('request')) return $default;
        $val = request()->input($name, $default);
        return ($val === null || trim((string) $val) === '') ? $default : $val;
    }
}
