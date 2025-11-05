<?php

namespace Tetthys\Input\Components;

final class File extends BaseInput
{
    public function render(): string
    {
        // File inputs never render a value for security.
        $class = trim(($this->attrs['class'] ?? '') . ($this->hasError() ? ' is-invalid' : ''));
        $attrs = array_merge($this->attrs, [
            'name' => $this->name,
            'type' => 'file',
            'class' => $class,
        ]);
        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
