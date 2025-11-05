<?php

namespace Tetthys\Input\Components;

final class CheckboxGroup extends BaseInput
{
    private string $errorKey;

    /** @param array<string,string> $options */
    public function __construct($ctx, string $name, private readonly array $options, array $attrs = [], mixed $default = null)
    {
        parent::__construct($ctx, $name, $attrs, $default);
        $this->errorKey = $name; // preserve logical field name

        if (!is_array($this->value)) {
            $this->value = $this->value !== null ? [(string)$this->value] : [];
        } else {
            $this->value = array_map('strval', $this->value);
        }
        $this->name = str_ends_with($name, '[]') ? $name : $name . '[]';
    }

    // Use original key for error lookups
    public function hasError(): bool
    {
        return $this->ctx->errors?->has($this->errorKey) ?? false;
    }

    /** @return list<string> */
    public function errors(): array
    {
        return $this->ctx->errors?->get($this->errorKey) ?? [];
    }

    public function render(): string
    {
        $selected = $this->value;
        $errClass = $this->errorClass(); // <- from BaseInput

        $wrap = '<div' . $this->htmlAttrs(['class' => trim('checkbox-group ' . ($this->attrs['class'] ?? ''))]) . '>';
        foreach ($this->options as $val => $label) {
            $id = ($this->attrs['id_prefix'] ?? rtrim($this->name, '[]')) . '_' . md5((string)$val);
            $checked = in_array((string)$val, $selected, true);
            $class = $errClass ?: null;

            $input = '<input' . $this->htmlAttrs([
                'type'    => 'checkbox',
                'name'    => $this->name,
                'id'      => $id,
                'value'   => (string)$val,
                'checked' => $checked,
                'class'   => $class,
            ]) . ' />';

            $lbl = '<label' . $this->htmlAttrs(['for' => $id]) . '>' . htmlspecialchars((string)$label, ENT_QUOTES) . '</label>';
            $wrap .= '<div class="checkbox">' . $input . ' ' . $lbl . '</div>';
        }
        return $wrap . '</div>';
    }
}
