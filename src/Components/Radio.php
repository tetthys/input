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
        $class = trim(($this->attrs['class'] ?? '') . ($this->hasError() ? ' is-invalid' : ''));
        $attrs = array_merge($this->attrs, [
            'name' => $this->name,
            'type' => 'radio',
            'value' => $this->radioValue,
            'class' => $class,
            'checked' => $checked,
        ]);
        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
