<?php

namespace Tetthys\Input\Components;

final class Select extends BaseInput
{
    /** @param array<string,string> $options value => label */
    public function __construct($ctx, string $name, private readonly array $options, array $attrs = [], mixed $default = null)
    {
        parent::__construct($ctx, $name, $attrs, $default);
    }

    public function render(): string
    {
        $class = trim(($this->attrs['class'] ?? '') . ($this->hasError() ? ' is-invalid' : ''));
        $attrs = array_merge($this->attrs, ['name' => $this->name, 'class' => $class]);
        $html = '<select' . $this->htmlAttrs($attrs) . ">\n";
        foreach ($this->options as $val => $label) {
            $selected = ((string)$val === (string)($this->value ?? '')) ? ' selected' : '';
            $html .= sprintf(
                "  <option value=\"%s\"%s>%s</option>\n",
                htmlspecialchars((string)$val, ENT_QUOTES),
                $selected,
                htmlspecialchars((string)$label, ENT_QUOTES)
            );
        }
        return $html . "</select>";
    }
}
