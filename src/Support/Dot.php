<?php

namespace Tetthys\Input\Support;

final class Dot
{
    /** Dot-notation getter for arrays/objects. */
    public static function get(array|object $target, string $path, mixed $default = null): mixed
    {
        $segments = explode('.', $path);
        $cursor = $target;
        foreach ($segments as $seg) {
            if (is_array($cursor)) {
                if (!array_key_exists($seg, $cursor)) return $default;
                $cursor = $cursor[$seg];
            } elseif (is_object($cursor)) {
                if (!isset($cursor->{$seg}) && !property_exists($cursor, $seg)) return $default;
                $cursor = $cursor->{$seg};
            } else {
                return $default;
            }
        }
        return $cursor;
    }
}
