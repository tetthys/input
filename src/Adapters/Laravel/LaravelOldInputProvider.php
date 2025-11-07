<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Contracts\ValueProvider;
use Tetthys\Input\Providers\OldInputProvider;
use Tetthys\Input\Support\Dot;

final class LaravelOldInputProvider
{
    public static function make(): ValueProvider
    {
        $has = static function (string $name): bool {
            if (!function_exists('session')) return false;
            $data = session()->get('_old_input', []);
            return Dot::get($data, $name, null) !== null;
        };

        $get = static function (string $name, mixed $default = null): mixed {
            if (!function_exists('session')) return $default;
            $data = session()->get('_old_input', []);
            return Dot::get($data, $name, $default);
        };

        return OldInputProvider::from($has, $get);
    }
}
