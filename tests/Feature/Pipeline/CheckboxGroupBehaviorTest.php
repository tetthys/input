<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Components\CheckboxGroup;

describe('CheckboxGroup: name[], ids, error with original key, escaping', function () {

    it('always uses [] suffix in name (even if user passed name[])', function () {
        $ctx = new InputContext(new ValueResolver());
        $opts = ['a' => 'Alpha'];
        $h1 = (new CheckboxGroup($ctx, 'tags',  $opts, default: ['a']))->render();
        $h2 = (new CheckboxGroup($ctx, 'tags[]', $opts, default: ['a']))->render();

        expect($h1)->toContain('name="tags[]"');
        expect($h2)->toContain('name="tags[]"');
    });

    it('normalizes scalars to array and checks only matched values', function () {
        $ctx = new InputContext(new ValueResolver());
        $opts = ['a' => 'Alpha', 'b' => 'Beta', 'c' => 'Gamma'];

        $html = (new CheckboxGroup($ctx, 'roles', $opts, default: 'b'))->render();
        expect(substr_count($html, ' checked'))->toBe(1);
        expect($html)->toContain('value="b"')->toContain('checked');
    });

    it('derives ids via id_prefix or rtrim(name,"[]") + "_" + md5(value)', function () {
        $ctx = new InputContext(new ValueResolver());
        $opts = ['a' => 'Alpha', 'c' => 'Gamma'];

        $withPrefix = (new CheckboxGroup($ctx, 'roles', $opts, attrs: ['id_prefix' => 'r'], default: ['a', 'c']))->render();
        expect($withPrefix)->toContain('id="r_' . md5('a') . '"')
            ->toContain('id="r_' . md5('c') . '"');

        $noPrefix = (new CheckboxGroup($ctx, 'roles', $opts, default: ['a']))->render();
        expect($noPrefix)->toContain('id="roles_' . md5('a') . '"');
    });

    it('uses original field name for ErrorStore (roles vs roles[]) and adds is-invalid per input', function () {
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'roles';
            } // original key
            public function get(string $name): array
            {
                return ['Bad'];
            }
        };
        $ctx = new InputContext(new ValueResolver(), $errors);
        $opts = ['x' => 'X', 'y' => 'Y', 'z' => 'Z'];

        $html = (new CheckboxGroup($ctx, 'roles', $opts, default: ['x']))->render();

        // Each <input> gets class="is-invalid"
        expect(substr_count($html, 'class="is-invalid"'))->toBe(3);
    });

    it('escapes label/value while preserving checked', function () {
        $ctx = new InputContext(new ValueResolver());
        $opts = ['a&b' => 'A&B', 'x' => '<X>'];

        $html = (new CheckboxGroup($ctx, 'features', $opts, default: ['a&b']))->render();
        expect($html)->toContain('value="a&amp;b"');
        expect($html)->toContain('>A&amp;B<');
        expect(substr_count($html, ' checked'))->toBe(1);
    });
});
