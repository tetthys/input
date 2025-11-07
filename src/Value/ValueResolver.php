<?php

namespace Tetthys\Input\Value;

use Tetthys\Input\Contracts\ValueProvider;

final class ValueResolver
{
    /** @var list<ValueProvider> */
    private array $providers = [];

    /** Mutable push; return $this for chaining. */
    public function with(ValueProvider $provider): self
    {
        $this->providers[] = $provider;
        return $this;
    }

    public function resolve(string $name, mixed $fallback = null): mixed
    {
        foreach ($this->providers as $p) {
            if ($p->has($name)) {
                return $p->get($name, $fallback);
            }
        }
        return $fallback;
    }
}
