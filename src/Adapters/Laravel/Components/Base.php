<?php

namespace Tetthys\Input\Adapters\Laravel\Components;

use Illuminate\View\Component;
use Tetthys\Input\Adapters\Laravel\InputContextFactory;

/** Base Blade component that returns raw HTML from core component. */
abstract class Base extends Component
{
    public function __construct(
        public string $name,
        /** @var array<string,mixed> */
        public array $attrs = [],
        public mixed $default = null,
    ) {}

    protected function ctx(): \Tetthys\Input\Context\InputContext
    {
        return InputContextFactory::make();
    }

    abstract public function renderHtml(): string;

    public function render()
    {
        return fn() => $this->renderHtml();
    }
}
