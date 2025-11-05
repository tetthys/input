<?php

namespace Tetthys\Input\Providers;

use Tetthys\Input\Contracts\ValueProvider;

/** Adapter by callables (e.g., old flashed inputs). */
final class OldInputProvider implements ValueProvider
{
    public function __construct(
        private readonly \Closure $has,
        private readonly \Closure $get,
    ) {}

    public static function from(callable $has, callable $get): self
    {
        return new self($has(...), $get(...));
    }

    public function has(string $name): bool
    {
        return ($this->has)($name);
    }
    public function get(string $name, mixed $default = null): mixed
    {
        return ($this->get)($name, $default);
    }
}
