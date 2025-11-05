<?php
// tests/Feature/Pipeline/SelectAdvancedBehaviorTest.php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Components\SelectAdvanced;

describe('SelectAdvanced: multiple/single normalization, escaping, errors', function () {

    it('multiple=true: always appends [] to name; default may be array or scalar', function () {
        $ctx = new InputContext(new ValueResolver());
        $opts = [
            ['label' => 'EU',   'options' => ['de' => 'Germany', 'fr' => 'France']],
            ['label' => 'APAC', 'options' => ['kr' => 'Korea',   'jp' => 'Japan']],
            'us' => 'USA',
        ];

        // default as scalar works and becomes an array internally
        $h1 = (new SelectAdvanced($ctx, 'countries', $opts, attrs: ['multiple' => true], default: 'fr'))->render();
        expect($h1)->toContain('name="countries[]"');
        expect(substr_count($h1, ' selected'))->toBe(1);
        expect($h1)->toContain('value="fr" selected');

        // default as array with two values
        $h2 = (new SelectAdvanced($ctx, 'countries', $opts, attrs: ['multiple' => true], default: ['fr', 'kr']))->render();
        expect($h2)->toContain('name="countries[]"');
        expect(substr_count($h2, ' selected'))->toBe(2);
        expect($h2)->toContain('value="fr" selected')->toContain('value="kr" selected');
    });

    it('single-select: array default selects only the first element', function () {
        $ctx = new InputContext(new ValueResolver());
        $opts = [
            ['label' => 'EU', 'options' => ['de' => 'Germany', 'fr' => 'France']],
            'us' => 'USA',
        ];

        $html = (new SelectAdvanced($ctx, 'c', $opts, default: ['fr', 'de']))->render();

        expect($html)->not->toContain('name="c[]"');  // single
        expect(substr_count($html, ' selected'))->toBe(1);
        expect($html)->toContain('value="fr" selected'); // first wins
    });

    it('escapes option values/labels and merges is-invalid class', function () {
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'safe';
            }
            public function get(string $name): array
            {
                return [];
            }
        };
        $ctx = new InputContext(new ValueResolver(), $errors);

        $opts = ['<a&>' => '"A&B"'];
        $html = (new SelectAdvanced($ctx, 'safe', $opts, ['class' => 'form'], default: '<a&>'))->render();

        expect($html)->toContain('class="form is-invalid"');
        expect($html)->toContain('value="&lt;a&amp;&gt;"');
        expect($html)->toContain('>&quot;A&amp;B&quot;<');
        expect(substr_count($html, ' selected'))->toBe(1);
    });
});
