<?php

namespace Tetthys\Input\Adapters\Laravel\Components;

use Tetthys\Input\Components\Password as C;

final class Password extends Base
{
    public function renderHtml(): string
    {
        return (new C($this->ctx(), $this->name, $this->attrs, $this->default))->render();
    }
}
