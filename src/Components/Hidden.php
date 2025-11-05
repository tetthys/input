<?php

namespace Tetthys\Input\Components;

final class Hidden extends BaseInput
{
    public function render(): string
    {
        $attrs = array_merge($this->attrs, [
            'name' => $this->name,
            'type' => 'hidden',
            'value' => (string)($this->value ?? ''),
        ]);
        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
