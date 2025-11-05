<?php

namespace Tetthys\Input\Components;

final class Textarea extends BaseInput
{
    public function render(): string
    {
        $base = $this->attrs['class'] ?? '';
        $err  = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, ['name' => $this->name, 'class' => $class]);
        $value = (string)($this->value ?? '');
        return '<textarea' . $this->htmlAttrs($attrs) . '>' . htmlspecialchars($value, ENT_QUOTES) . '</textarea>';
    }
}
