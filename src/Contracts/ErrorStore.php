<?php

namespace Tetthys\Input\Contracts;

interface ErrorStore
{
    public function has(string $name): bool;

    /** @return list<string> */
    public function get(string $name): array;
}
