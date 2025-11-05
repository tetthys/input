<?php

namespace Tetthys\Input\Context;

use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Contracts\ErrorStyler;
use Tetthys\Input\Value\ValueResolver;

final class InputContext
{
    public function __construct(
        public readonly ValueResolver $resolver,
        public readonly ?ErrorStore $errors = null,
        public readonly ?ErrorStyler $styler = null,
    ) {}
}
