<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Providers\OldInputProvider;

final class LaravelOldInputProvider extends OldInputProvider
{
    public static function make(): self
    {
        $has = static function (string $name): bool {
            if (!function_exists('session')) return false;
            $old = session()->get('_old_input', []);
            return array_key_exists($name, $old);
        };
        $get = static function (string $name, mixed $default = null): mixed {
            if (!function_exists('session')) return $default;
            $old = session()->get('_old_input', []);
            return $old[$name] ?? $default;
        };
        return parent::from($has, $get);
    }
}
