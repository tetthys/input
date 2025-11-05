<?php

namespace Tetthys\Input\Support;

use Tetthys\Input\Contracts\ErrorStyler;

final class DefaultErrorStyler implements ErrorStyler
{
    public function __construct(private readonly string $class = 'is-invalid') {}

    public function classFor(string $name, array $messages): ?string
    {
        return $this->class; // always append this class if there is any error
    }
}
