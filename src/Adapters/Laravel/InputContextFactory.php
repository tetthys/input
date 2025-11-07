<?php

namespace Tetthys\Input\Adapters\Laravel;

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;

final class InputContextFactory
{
    public static function make(): InputContext
    {
        $resolver = (new ValueResolver())
            ->with(LaravelOldInputProvider::make())
            ->with(new LaravelRequestValueProvider());

        return new InputContext($resolver, errors: new LaravelErrorStore());
    }
}
