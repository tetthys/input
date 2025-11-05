<?php

namespace Tetthys\Input\Components;

final class Time extends BaseInput
{
    public function render(): string
    {
        $base = $this->attrs['class'] ?? '';
        $err  = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, [
            'name' => $this->name,
            'type' => 'time',
            'value' => (string)($this->value ?? ''),
            'class' => $class,
        ]);
        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
