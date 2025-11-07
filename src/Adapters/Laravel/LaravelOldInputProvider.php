<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Contracts\ValueProvider;
use Tetthys\Input\Providers\OldInputProvider;

final class LaravelOldInputProvider
{
    public static function make(): ValueProvider
    {
        $has = static function (string $name): bool {
            if (!function_exists('old')) return false;
            $val = old($name);
            return ($val !== null && trim((string) $val) !== '');
        };

        $get = static function (string $name, mixed $default = null): mixed {
            if (!function_exists('old')) return $default;
            $val = old($name, $default);
            return ($val === null || trim((string) $val) === '') ? $default : $val;
        };

        return OldInputProvider::from($has, $get);
    }
}
