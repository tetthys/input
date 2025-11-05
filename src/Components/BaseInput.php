<?php

namespace Tetthys\Input\Components;

use Tetthys\Input\Context\InputContext;

abstract class BaseInput
{
    protected string $name;
    protected mixed $value = null;
    protected array $attrs = [];

    public function __construct(
        protected readonly InputContext $ctx,
        string $name,
        array $attrs = [],
        mixed $default = null,
    ) {
        $this->name = $name;
        $this->attrs = $attrs;
        $this->value = $ctx->resolver->resolve($name, $default);
    }

    public function hasError(): bool
    {
        return $this->ctx->errors?->has($this->name) ?? false;
    }

    /** @return list<string> */
    public function errors(): array
    {
        return $this->ctx->errors?->get($this->name) ?? [];
    }

    abstract public function render(): string;

    /** Build HTML attributes string. */
    protected function htmlAttrs(array $extra = []): string
    {
        $all = array_merge($this->attrs, $extra);
        $parts = [];
        foreach ($all as $k => $v) {
            if ($v === true) {
                $parts[] = htmlspecialchars((string)$k, ENT_QUOTES);
                continue;
            }
            if ($v === false || $v === null) continue;
            $parts[] = sprintf('%s="%s"', htmlspecialchars((string)$k, ENT_QUOTES), htmlspecialchars((string)$v, ENT_QUOTES));
        }
        return $parts ? ' ' . implode(' ', $parts) : '';
    }
}
