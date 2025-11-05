<?php

use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Providers\{ExplicitValueProvider, ModelValueProvider, OldInputProvider, RequestValueProvider};
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\Text;

describe('Usage: resolver pipeline', function () {
    it('prefers old > request > explicit > model > default', function () {
        // Setup providers in priority order
        $old = OldInputProvider::from(
            has: fn(string $k) => $k === 'user.name',
            get: fn(string $k, $d = null) => 'OLD',
        );
        $req = new RequestValueProvider(['user' => ['name' => 'REQ']]);
        $exp = new ExplicitValueProvider(['user' => ['name' => 'EXP']]);
        $mod = new ModelValueProvider((object)['user' => (object)['name' => 'MODEL']]);

        $resolver = (new ValueResolver())->with($old)->with($req)->with($exp)->with($mod);
        $ctx = new InputContext($resolver);

        $html = (new Text($ctx, 'user.name', default: 'DEFAULT'))->render();
        expect($html)->toContain('value="OLD"');
    });

    it('falls back to default when unresolved', function () {
        $resolver = new ValueResolver(); // no providers
        $ctx = new InputContext($resolver);

        $html = (new Text($ctx, 'profile.nick', default: 'guest'))->render();
        expect($html)->toContain('value="guest"');
    });

    it('adds error class when ErrorStore says the field has errors', function () {
        $resolver = new ValueResolver();
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'email';
            }
            public function get(string $name): array
            {
                return $name === 'email' ? ['Invalid.'] : [];
            }
        };
        $ctx = new InputContext($resolver, errors: $errors);

        $html = (new Text($ctx, 'email', default: 'a@b.c'))->render();
        expect($html)->toContain('is-invalid')->toContain('value="a@b.c"');
    });
});
