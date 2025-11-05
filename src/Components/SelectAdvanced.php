<?php

namespace Tetthys\Input\Components;

/**
 * Options format:
 * - Simple: ['v1' => 'Label1', 'v2' => 'Label2']
 * - Optgroup: [['label'=>'G1','options'=>['a'=>'A','b'=>'B']], 'c'=>'C']
 * - Mixed allowed.
 */
final class SelectAdvanced extends BaseInput
{
    /** @param array<int|string,mixed> $options */
    public function __construct($ctx, string $name, private readonly array $options, array $attrs = [], mixed $default = null)
    {
        parent::__construct($ctx, $name, $attrs, $default);

        $isMultiple = (bool)($this->attrs['multiple'] ?? false);

        if ($isMultiple) {
            // Always [] when multiple
            $this->name = str_ends_with($name, '[]') ? $name : $name . '[]';

            // Normalize to list<string>
            if (!is_array($this->value)) {
                $this->value = $this->value !== null ? [(string)$this->value] : [];
            } else {
                $this->value = array_map('strval', $this->value);
            }
        } else {
            // Single-select: if array given (from pipeline/default), use only the first element
            if (is_array($this->value)) {
                $first = array_key_first($this->value);
                $this->value = $first !== null ? (string)$this->value[$first] : '';
            } else {
                $this->value = (string)($this->value ?? '');
            }
        }
    }

    private function isSelected(string $v): bool
    {
        $isMultiple = (bool)($this->attrs['multiple'] ?? false);

        if ($isMultiple) {
            /** @var list<string> $cur */
            $cur = array_map('strval', (array)$this->value);
            return in_array($v, $cur, true);
        }

        // single: $this->value normalized to string
        /** @var string $cur */
        $cur = (string)$this->value;
        return $v === $cur;
    }

    public function render(): string
    {
        $class = trim(($this->attrs['class'] ?? '') . ($this->hasError() ? ' is-invalid' : ''));
        $attrs = array_merge($this->attrs, ['name' => $this->name, 'class' => $class]);
        $html = '<select' . $this->htmlAttrs($attrs) . ">\n";

        foreach ($this->options as $key => $item) {
            if (is_array($item) && array_key_exists('options', $item)) {
                // Optgroup
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
