<?php

namespace Tetthys\Input\Value;

use Tetthys\Input\Contracts\ValueProvider;

final class ValueResolver
{
    /** @var list<ValueProvider> */
    private array $providers = [];

    /** Register providers in priority (first wins). */
    public function with(ValueProvider $provider): self
    {
        $clone = clone $this;
        $clone->providers[] = $provider;
        return $clone;
    }

    public function resolve(string $name, mixed $fallback = null): mixed
    {
        foreach ($this->providers as $p) {
            if ($p->has($name)) return $p->get($name, $fallback);
        }
        return $fallback;
    }
}
