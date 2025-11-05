<?php

namespace Tetthys\Input\Components;

final class Hidden extends BaseInput
{
    public function render(): string
    {
        $attrs = array_merge($this->attrs, [
            'name'  => $this->name,
            'type'  => 'hidden',
            'value' => (string)($this->value ?? ''),
            // no errorClass() for hidden
        ]);

        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}
