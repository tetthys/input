<?php

namespace Tetthys\Input\Adapters\Laravel\Components;

use Tetthys\Input\Components\CheckboxGroup as C;

final class CheckboxGroup extends Base
{
    /** @param array<string,string> $options */
    public function __construct(string $name, public array $options = [], public array $attrs = [], public mixed $default = null)
    {
        parent::__construct($name, $attrs, $default);
    }
    public function renderHtml(): string
    {
        return (new C($this->ctx(), $this->name, $this->options, $this->attrs, $this->default))->render();
    }
}
