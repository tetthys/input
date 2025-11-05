<?php

namespace Tetthys\Input\Components;

final class Checkbox extends BaseInput
{
    public function __construct($ctx, string $name, array $attrs = [], mixed $default = null, private readonly string $checkedValue = '1')
    {
        parent::__construct($ctx, $name, $attrs, $default);
    }

    public function render(): string
    {
        $checked = ((string)$this->value === (string)$this->checkedValue);
        $class = trim(($this->attrs['class'] ?? '') . ($this->hasError() ? ' is-invalid' : ''));
        $attrs = array_merge($this->attrs, [
            'name' => $this->name,
            'type' => 'checkbox',
            'value' => $this->checkedValue,
            'class' => $class,
            'checked' => $checked,
        ]);
        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
