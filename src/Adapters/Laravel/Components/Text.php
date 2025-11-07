<?php

namespace Tetthys\Input\Adapters\Laravel\Components;

use Tetthys\Input\Components\Text as C;

final class Text extends Base
{
    public function renderHtml(): string
    {
        return (new C($this->ctx(), $this->name, $this->attrs, $this->default))->render();
    }
}
