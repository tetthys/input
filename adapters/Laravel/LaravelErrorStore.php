<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Contracts\ErrorStore;

final class LaravelErrorStore implements ErrorStore
{
    public function has(string $name): bool
    {
        if (!function_exists('session')) return false;
        /** @var \Illuminate\Support\ViewErrorBag|null $bag */
        $bag = session('errors');
        return $bag?->has($name) ?? false;
    }

    public function get(string $name): array
    {
        if (!function_exists('session')) return [];
        /** @var \Illuminate\Support\ViewErrorBag|null $bag */
        $bag = session('errors');
        return $bag?->get($name, []) ?? [];
    }
}
