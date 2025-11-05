<?php

namespace Tetthys\Input\Components;

final class Password extends BaseInput
{
    public function render(): string
    {
        $class = trim(($this->attrs['class'] ?? '') . ($this->hasError() ? ' is-invalid' : ''));
        $attrs = array_merge($this->attrs, [
            'name' => $this->name,
            'type' => 'password',
            // For security: no value unless explicitly allowed by `prefill`.
            'value' => ($this->attrs['prefill'] ?? false) ? (string)($this->value ?? '') : null,
            'class' => $class,
        ]);
        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
