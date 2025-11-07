<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Contracts\ErrorStore;

final class LaravelErrorStore implements ErrorStore
{
    public function has(string $name): bool
    {
        if (!function_exists('session')) return false;
        $bag = session('errors');
        return $bag?->has($name) ?? false;
    }

    /** @return list<string> */
    public function get(string $name): array
    {
        if (!function_exists('session')) return [];
        $bag = session('errors');
        $messages = $bag?->get($name, []) ?? [];

        // Filter out empty messages
        return array_values(array_filter($messages, fn($m) => trim((string) $m) !== ''));
    }
}
