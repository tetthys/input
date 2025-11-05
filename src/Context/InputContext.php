<?php

namespace Tetthys\Input\Context;

use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Value\ValueResolver;

final class InputContext
{
    public function __construct(
        public readonly ValueResolver $resolver,
        public readonly ?ErrorStore $errors = null
    ) {}
}
