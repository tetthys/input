<?php

namespace Tetthys\Input\Components;

final class SelectAdvanced extends BaseInput
{
    /** @param array<int|string,mixed> $options */
    public function __construct($ctx, string $name, private readonly array $options, array $attrs = [], mixed $default = null)
    {
        parent::__construct($ctx, $name, $attrs, $default);

        $isMultiple = (bool)($this->attrs['multiple'] ?? false);
        if ($isMultiple) {
            $this->name = str_ends_with($name, '[]') ? $name : $name . '[]';
            $this->value = is_array($this->value)
                ? array_map('strval', $this->value)
                : ($this->value !== null ? [(string)$this->value] : []);
        } else {
            $this->value = is_array($this->value)
                ? ((null !== ($k = array_key_first($this->value))) ? (string)$this->value[$k] : '')
                : (string)($this->value ?? '');
        }
    }

    private function isSelected(string $v): bool
    {
        $isMultiple = (bool)($this->attrs['multiple'] ?? false);
        if ($isMultiple) {
            return in_array($v, array_map('strval', (array)$this->value), true);
        }
        return $v === (string)$this->value;
    }

    public function render(): string
    {
        $base = $this->attrs['class'] ?? '';
        $err  = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, ['name' => $this->name, 'class' => $class ?: null]);
        $html = '<select' . $this->htmlAttrs($attrs) . ">\n";

        $isMultiple = (bool)($this->attrs['multiple'] ?? false);

        // --- Placeholder option (only when NOT multiple)
        if (!$isMultiple && isset($this->attrs['placeholder'])) {
            $phText   = (string)$this->attrs['placeholder'];
            $phValue  = array_key_exists('placeholder_value', $this->attrs) ? (string)$this->attrs['placeholder_value'] : '';
            $selectable = (bool)($this->attrs['placeholder_selectable'] ?? false);
            $hidden     = (bool)($this->attrs['placeholder_hidden'] ?? false);

            $sel = ($this->value === '' || $this->value === null) ? ' selected' : '';
            $dis = $selectable ? '' : ' disabled';
            $hid = $hidden ? ' hidden' : '';

            $html .= sprintf(
                '  <option value="%s"%s%s%s>%s</option>' . "\n",
                htmlspecialchars($phValue, ENT_QUOTES),
                $sel,
                $dis,
                $hid,
                htmlspecialchars($phText, ENT_QUOTES)
            );
        }

        // --- Options / Optgroups
        foreach ($this->options as $key => $item) {
            if (is_array($item) && array_key_exists('options', $item)) {
                $label = htmlspecialchars((string)($item['label'] ?? $key), ENT_QUOTES);
                $html .= "<optgroup label=\"{$label}\">\n";
                foreach (($item['options'] ?? []) as $val => $lab) {
                    $html .= sprintf(
                        "  <option value=\"%s\"%s>%s</option>\n",
                        htmlspecialchars((string)$val, ENT_QUOTES),
                        $this->isSelected((string)$val) ? ' selected' : '',
                        htmlspecialchars((string)$lab, ENT_QUOTES)
                    );
                }
                $html .= "</optgroup>\n";
            } else {
                $val = htmlspecialchars((string)$key, ENT_QUOTES);
                $lab = htmlspecialchars((string)$item, ENT_QUOTES);
                $sel = $this->isSelected((string)$key) ? ' selected' : '';
                $html .= "  <option value=\"{$val}\"{$sel}>{$lab}</option>\n";
            }
        }

        return $html . "</select>";
    }
}
