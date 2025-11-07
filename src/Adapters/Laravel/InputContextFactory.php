<?php

namespace Tetthys\Input\Adapters\Laravel;

use Illuminate\Contracts\Container\Container;
use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Contracts\ErrorStyler;
use Tetthys\Input\Contracts\ValueProvider;
use Tetthys\Input\Value\ValueResolver;

final class InputContextFactory
{
    /**
     * Build InputContext using container (preferred) or legacy static wiring.
     */
    public static function make(?Container $app = null): InputContext
    {
        if ($app === null && function_exists('app')) {
            $app = app();
        }

        if ($app) {
            $resolver = new ValueResolver();

            /** @var iterable<ValueProvider> $providers */
            $providers = $app->tagged('tetinput.value_providers');
            foreach ($providers as $p) {
                $resolver->with($p);
            }

            /** @var ErrorStore|null $errors */
            $errors = $app->bound(ErrorStore::class) ? $app->make(ErrorStore::class) : null;

            /** @var ErrorStyler|null $styler */
            $styler = $app->bound(ErrorStyler::class) ? $app->make(ErrorStyler::class) : null;

            return new InputContext($resolver, errors: $errors, styler: $styler);
        }

        // Legacy fallback (no container)
        $resolver = (new ValueResolver())
            ->with(LaravelOldInputProvider::make())
            ->with(new LaravelRequestValueProvider());

        return new InputContext($resolver, errors: new LaravelErrorStore());
    }
}
