<?php

namespace Tetthys\Input\Adapters\Laravel\Components;

use Tetthys\Input\Components\Number as C;

final class Number extends Base
{
    public function renderHtml(): string
    {
        return (new C($this->ctx(), $this->name, $this->attrs, $this->default))->render();
    }
}
