<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\Text;
use Tetthys\Input\Providers\{
    OldInputProvider,
    RequestValueProvider,
    ExplicitValueProvider,
    ModelValueProvider
};

describe('Pipeline integration with Text', function () {

    it('Text renders the top-most provider value', function () {
        $mod = new ModelValueProvider((object)['meta' => (object)['title' => 'M']]);
        $exp = new ExplicitValueProvider(['meta' => ['title' => 'E']]);
        $req = new RequestValueProvider(['meta' => ['title' => 'R']]);
        $old = OldInputProvider::from(
            has: fn(string $k) => $k === 'meta.title',
            get: fn(string $k, $d = null) => 'O'
        );

        $resolver = (new ValueResolver())->with($old)->with($req)->with($exp)->with($mod);
        $ctx = new InputContext($resolver);

        $html = (new Text($ctx, 'meta.title', default: 'D'))->render();
        expect($html)->toContain('value="O"');
    });

    it('Text falls back to default if path does not exist', function () {
        $resolver = (new ValueResolver())->with(new ExplicitValueProvider(['meta' => ['other' => 'X']]));
        $ctx = new InputContext($resolver);

        $html = (new Text($ctx, 'meta.title', default: 'D'))->render();
        expect($html)->toContain('value="D"');
    });
});
