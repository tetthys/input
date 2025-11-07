<?php

namespace Tetthys\Input\Adapters\Laravel\Components;

use Tetthys\Input\Components\Hidden as C;

final class Hidden extends Base
{
    public function renderHtml(): string
    {
        return (new C($this->ctx(), $this->name, $this->attrs, $this->default))->render();
    }
}
