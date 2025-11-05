<?php

namespace Tetthys\Input\Components;

final class Number extends BaseInput
{
    public function render(): string
    {
        $base = $this->attrs['class'] ?? '';
        $err  = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, [
            'name' => $this->name,
            'type' => 'number',
            'value' => (string)($this->value ?? ''),
            'class' => $class,
        ]);
        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
