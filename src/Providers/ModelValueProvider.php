<?php

namespace Tetthys\Input\Providers;

use Tetthys\Input\Contracts\ValueProvider;
use Tetthys\Input\Support\Dot;

final class ModelValueProvider implements ValueProvider
{
    public function __construct(private readonly array|object $model) {}

    public function has(string $name): bool
    {
        return Dot::get($this->model, $name, null) !== null;
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return Dot::get($this->model, $name, $default);
    }
}
