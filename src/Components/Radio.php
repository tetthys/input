<?php

namespace Tetthys\Input\Components;

final class Radio extends BaseInput
{
    public function __construct($ctx, string $name, array $attrs = [], mixed $default = null, private readonly string $radioValue = '1')
    {
        parent::__construct($ctx, $name, $attrs, $default);
    }

    public function render(): string
    {
        $checked = $this->isChecked($this->value, $this->radioValue);

        $base  = $this->attrs['class'] ?? '';
        $err   = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, [
            'name'    => $this->name,
            'type'    => 'radio',
            'value'   => $this->radioValue,
            'class'   => $class ?: null,
            'checked' => $checked,
        ]);

        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }

    /** Robust checked comparator accommodating arrays/scalars/booleans. */
    private function isChecked(mixed $current, string $radioValue): bool
    {
        $want = (string) $radioValue;

        if (is_array($current)) {
            // If array, consider checked when any element matches.
            foreach ($current as $v) {
                if ((string) $v === $want) {
                    return true;
                }
            }
            return false;
        }

        if (is_bool($current)) {
            return ($current ? '1' : '0') === $want;
        }

        if (is_scalar($current) || (is_object($current) && method_exists($current, '__toString'))) {
            return (string) $current === $want;
        }

        // Fallback: not comparable
        return false;
    }
}
