<?php

namespace Tetthys\Input\Components;

final class File extends BaseInput
{
    public function render(): string
    {
        $base  = $this->attrs['class'] ?? '';
        $err   = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, [
            'name'  => $this->name,
            'type'  => 'file',
            'class' => $class ?: null,
            // no value for file inputs
        ]);

        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
