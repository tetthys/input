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

describe('Resolver preference order: old > request > explicit > model > default', function () {

    it('uses the highest priority provider (OLD)', function () {
        $old = OldInputProvider::from(
            has: fn(string $k) => $k === 'user.name',
            get: fn(string $k, $d = null) => 'OLD'
        );
        $req = new RequestValueProvider(['user' => ['name' => 'REQ']]);
        $exp = new ExplicitValueProvider(['user' => ['name' => 'EXP']]);
        $mod = new ModelValueProvider((object)['user' => (object)['name' => 'MODEL']]);

        $resolver = (new ValueResolver())->with($old)->with($req)->with($exp)->with($mod);
        $ctx = new InputContext($resolver);

        $html = (new Text($ctx, 'user.name', default: 'DEFAULT'))->render();
        expect($html)->toContain('value="OLD"');
    });

    it('falls back to default if nothing resolves', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Text($ctx, 'profile.nick', default: 'guest'))->render();

        expect($html)->toContain('value="guest"');
    });
});
