<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\Text;
use Tetthys\Input\Providers\ExplicitValueProvider;

describe('Resolver edge values (truthy/falsy/null)', function () {

    it('respects "0" and 0 (should not fall back)', function () {
        $r1 = (new ValueResolver())->with(new ExplicitValueProvider(['a' => ['code' => '0']]));
        $r2 = (new ValueResolver())->with(new ExplicitValueProvider(['a' => ['age'  => 0]]));

        $h1 = (new Text(new InputContext($r1), 'a.code', default: 'FB'))->render();
        $h2 = (new Text(new InputContext($r2), 'a.age',  default: 9))->render();

        expect($h1)->toContain('value="0"');
        expect($h2)->toContain('value="0"');
    });

    it('treats null as unresolved and uses default', function () {
        $r = (new ValueResolver())->with(new ExplicitValueProvider(['u' => ['name' => null]]));
        $html = (new Text(new InputContext($r), 'u.name', default: 'anon'))->render();

        expect($html)->toContain('value="anon"');
    });
});
