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

    /**
     * Resolve error class:
     * - Use styler if present (null/empty => no class).
     * - If single token and 'bem_block' exists, expand to "{block}--{token}".
     * - If no styler, fallback to "is-invalid" with the same BEM rule.
     */
    protected function errorClass(): ?string
    {
        if (!$this->hasError()) {
            return null;
        }

        $messages = $this->errors();
        $token = null;

        if ($this->ctx->styler !== null) {
            $token = $this->ctx->styler->classFor($this->name, $messages);
            if ($token === null || $token === '') {
                return null; // respect "no class"
            }
        } else {
            $token = 'is-invalid';
        }

        // If multiple classes are returned (space-separated), use as-is.
        if (str_contains($token, ' ')) {
            return $token;
        }

        // BEM expansion when a block hint is provided.
        $block = $this->attrs['bem_block'] ?? null;
        if ($block) {
            return "{$block}--{$token}";
        }

        return $token;
    }

    abstract public function render(): string;

    /** Build HTML attributes string. */
    protected function htmlAttrs(array $extra = []): string
    {
        $all = array_merge($this->attrs, $extra);
        $parts = [];
        foreach ($all as $key => $val) {
            if ($val === true) {
                $parts[] = htmlspecialchars((string) $key, ENT_QUOTES);
                continue;
            }
            if ($val === false || $val === null) continue;
            $parts[] = sprintf(
                '%s="%s"',
                htmlspecialchars((string) $key, ENT_QUOTES),
                htmlspecialchars((string) $val, ENT_QUOTES)
            );
        }
        return $parts ? ' ' . implode(' ', $parts) : '';
    }
}
