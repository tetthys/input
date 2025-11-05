<?php

namespace Tetthys\Input\Components;

final class CheckboxGroup extends BaseInput
{
    /** @param array<string,string> $options */
    public function __construct($ctx, string $name, private readonly array $options, array $attrs = [], mixed $default = null)
    {
        parent::__construct($ctx, $name, $attrs, $default);
        if (!is_array($this->value)) $this->value = $this->value !== null ? [(string)$this->value] : [];
        $this->name = str_ends_with($name, '[]') ? $name : $name . '[]';
    }

    public function render(): string
    {
        $selected = array_map('strval', $this->value);
        $wrap = '<div' . $this->htmlAttrs(['class' => trim('checkbox-group ' . ($this->attrs['class'] ?? ''))]) . '>';
        foreach ($this->options as $val => $label) {
            $id = ($this->attrs['id_prefix'] ?? rtrim($this->name, '[]')) . '_' . md5((string)$val);
            $checked = in_array((string)$val, $selected, true);
            $input = '<input' . $this->htmlAttrs([
                'type' => 'checkbox',
                'name' => $this->name,
                'id'   => $id,
                'value' => (string)$val,
                'checked' => $checked,
                'class' => $this->hasError() ? 'is-invalid' : null,
            ]) . ' />';
            $lbl = '<label' . $this->htmlAttrs(['for' => $id]) . '>' . htmlspecialchars((string)$label, ENT_QUOTES) . '</label>';
            $wrap .= '<div class="checkbox">' . $input . ' ' . $lbl . '</div>';
        }
        return $wrap . '</div>';
    }
}
