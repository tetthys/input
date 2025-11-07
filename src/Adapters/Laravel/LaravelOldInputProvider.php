<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Contracts\ValueProvider;
use Tetthys\Input\Providers\OldInputProvider;

final class LaravelOldInputProvider
{
    public static function make(): ValueProvider
    {
        $has = static fn(string $name): bool =>
        function_exists('old') && old($name) !== null;

        $get = static fn(string $name, mixed $default = null): mixed =>
        function_exists('old') ? old($name, $default) : $default;

        return OldInputProvider::from($has, $get);
    }
}
