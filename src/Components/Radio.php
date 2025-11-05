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
        $checked = ((string)$this->value === (string)$this->radioValue);

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
}
