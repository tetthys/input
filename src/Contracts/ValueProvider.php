<?php

namespace Tetthys\Input\Contracts;

interface ValueProvider
{
    /** True if a value exists for the given name. */
    public function has(string $name): bool;

    /** Get value or default. */
    public function get(string $name, mixed $default = null): mixed;
}
