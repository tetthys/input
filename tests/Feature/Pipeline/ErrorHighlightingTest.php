<?php

use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\{Text, CheckboxGroup};

describe('Error highlighting with pipeline', function () {

    it('Text appends "is-invalid" class when ErrorStore reports error', function () {
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'email';
            }
            public function get(string $name): array
            {
                return ['Invalid.'];
            }
        };
        $ctx = new InputContext(new ValueResolver(), $errors);

        $html = (new Text($ctx, 'email', attrs: ['class' => 'form'], default: 'a@b.c'))->render();

        expect($html)->toContain('class="form is-invalid"');
        expect($html)->toContain('value="a@b.c"');
    });

    it('CheckboxGroup applies "is-invalid" per input when field has error', function () {
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'roles';
            }
            public function get(string $name): array
            {
                return ['Required.'];
            }
        };
        $ctx = new InputContext(new ValueResolver(), $errors);
        $opts = ['a' => 'Alpha', 'b' => 'Beta'];

        $html = (new CheckboxGroup($ctx, 'roles', $opts, default: ['a']))->render();

        // Each <input> carries class="is-invalid". We assert at least 2 occurrences.
        expect(substr_count($html, 'class="is-invalid"'))->toBeGreaterThanOrEqual(2);
    });
});
