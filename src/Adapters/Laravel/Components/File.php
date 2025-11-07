<?php

namespace Tetthys\Input\Adapters\Laravel\Components;

use Tetthys\Input\Components\File as C;

final class File extends Base
{
    public function renderHtml(): string
    {
        return (new C($this->ctx(), $this->name, $this->attrs, $this->default))->render();
    }
}
