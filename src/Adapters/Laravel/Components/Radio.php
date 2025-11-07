<?php

namespace Tetthys\Input\Adapters\Laravel\Components;

use Tetthys\Input\Components\Radio as C;

final class Radio extends Base
{
    public function __construct(string $name, public array $attrs = [], public mixed $default = null, public string $radioValue = '1')
    {
        parent::__construct($name, $attrs, $default);
    }
    public function renderHtml(): string
    {
        return (new C($this->ctx(), $this->name, $this->attrs, $this->default, $this->radioValue))->render();
    }
}
