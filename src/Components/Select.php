<?php

namespace Tetthys\Input\Components;

final class Select extends BaseInput
{
    /** @param array<string,string> $options */
    public function __construct($ctx, string $name, private readonly array $options, array $attrs = [], mixed $default = null)
    {
        parent::__construct($ctx, $name, $attrs, $default);
        // Normalize: array → first element, else string
        if (is_array($this->value)) {
            $first = array_key_first($this->value);
            $this->value = $first !== null ? (string)$this->value[$first] : '';
        } else {
            $this->value = (string)($this->value ?? '');
        }
    }

    public function render(): string
    {
        $base  = $this->attrs['class'] ?? '';
        $err   = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, ['name' => $this->name, 'class' => $class ?: null]);
        $html = '<select' . $this->htmlAttrs($attrs) . ">\n";
        foreach ($this->options as $val => $label) {
            $selected = ((string)$val === (string)$this->value) ? ' selected' : '';
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
