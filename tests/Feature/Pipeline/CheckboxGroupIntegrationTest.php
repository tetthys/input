<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\CheckboxGroup;
use Tetthys\Input\Providers\{
    OldInputProvider,
    RequestValueProvider,
    ExplicitValueProvider,
    ModelValueProvider
};

it('CheckboxGroup prefers resolver value (array/scalar) over default and checks accordingly', function () {
    // resolver provides ['b','g'] => both checked; default should be ignored.
    $opts = ['a' => 'Alpha', 'b' => 'Beta', 'g' => 'Gamma'];

    $mod = new ModelValueProvider((object)['tags' => 'g']);                 // scalar → normalized to ['g']
    $exp = new ExplicitValueProvider(['tags' => ['b', 'g']]);               // overrides model
    $req = new RequestValueProvider([]);                                     // no-op
    $old = OldInputProvider::from(has: fn($k) => false, get: fn($k, $d = null) => $d); // no-op

    $resolver = (new ValueResolver())->with($old)->with($req)->with($exp)->with($mod);
    $ctx = new InputContext($resolver);

    $html = (new CheckboxGroup($ctx, 'tags', $opts, default: ['a']))->render();

    expect($html)->toContain('name="tags[]"');            // multiple name
    expect($html)->toContain('value="b"')->toContain('checked');
    expect($html)->toContain('value="g"')->toContain('checked');
    // 'a' present but unselected
    expect($html)->toContain('value="a"');
    // ensure exactly 2 selections
    expect(substr_count($html, ' checked'))->toBe(2);
});
