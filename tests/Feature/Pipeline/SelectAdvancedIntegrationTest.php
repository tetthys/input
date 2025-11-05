<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\SelectAdvanced;
use Tetthys\Input\Providers\{
    OldInputProvider,
    RequestValueProvider,
    ExplicitValueProvider,
    ModelValueProvider
};

describe('Pipeline integration with SelectAdvanced', function () {

    it('multiple=true: resolver-provided list overrides default list', function () {
        $opts = [
            ['label' => 'EU',   'options' => ['de' => 'Germany', 'fr' => 'France']],
            ['label' => 'APAC', 'options' => ['kr' => 'Korea',   'jp' => 'Japan']],
            'us' => 'USA',
        ];
        $mod = new ModelValueProvider((object)['countries' => ['us']]);
        $exp = new ExplicitValueProvider(['countries' => ['fr', 'kr']]);
        $resolver = (new ValueResolver())->with(
            OldInputProvider::from(has: fn($k) => false, get: fn($k, $d = null) => $d)
        )->with(new RequestValueProvider([]))
            ->with($exp)
            ->with($mod);

        $ctx = new InputContext($resolver);
        $html = (new SelectAdvanced($ctx, 'countries', $opts, attrs: ['multiple' => true], default: ['us']))->render();

        expect($html)->toContain('name="countries[]"');
        expect(substr_count($html, ' selected'))->toBe(2);
        expect($html)->toContain('value="fr" selected');
        expect($html)->toContain('value="kr" selected');
        expect($html)->toContain('value="us"'); // present but not selected
    });

    it('single-select: selects at most one value even if resolver returns an array', function () {
        $opts = [
            ['label' => 'EU', 'options' => ['de' => 'Germany', 'fr' => 'France']],
            'us' => 'USA',
        ];
        $resolver = (new ValueResolver())->with(new ExplicitValueProvider(['c' => ['fr', 'de']]));
        $ctx = new InputContext($resolver);

        $html = (new SelectAdvanced($ctx, 'c', $opts, default: 'us'))->render();

        expect($html)->not->toContain('name="c[]"');
        expect(substr_count($html, ' selected'))->toBe(1);
        expect($html)->satisfy(fn($s) => str_contains($s, 'value="fr" selected') || str_contains($s, 'value="de" selected'));
    });
});
