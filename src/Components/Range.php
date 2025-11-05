<?php

namespace Tetthys\Input\Components;

final class Range extends BaseInput
{
    public function render(): string
    {
        $class = trim(($this->attrs['class'] ?? '') . ($this->hasError() ? ' is-invalid' : ''));
        $attrs = array_merge($this->attrs, [
            'name' => $this->name,
            'type' => 'range',
            'value' => (string)($this->value ?? ''),
            'class' => $class,
        ]);
        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
