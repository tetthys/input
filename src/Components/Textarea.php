<?php

namespace Tetthys\Input\Components;

final class Textarea extends BaseInput
{
    public function render(): string
    {
        $class = trim(($this->attrs['class'] ?? '') . ($this->hasError() ? ' is-invalid' : ''));
        $attrs = array_merge($this->attrs, ['name' => $this->name, 'class' => $class]);
        $value = (string)($this->value ?? '');
        return '<textarea' . $this->htmlAttrs($attrs) . '>' . htmlspecialchars($value, ENT_QUOTES) . '</textarea>';
    }
}
