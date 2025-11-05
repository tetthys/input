<?php

namespace Tetthys\Input\Components;

final class Password extends BaseInput
{
    public function render(): string
    {
        $base  = $this->attrs['class'] ?? '';
        $err   = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, [
            'name'  => $this->name,
            'type'  => 'password',
            'value' => ($this->attrs['prefill'] ?? false) ? (string)($this->value ?? '') : null,
            'class' => $class ?: null,
        ]);

        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
