<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Contracts\{ErrorStore, ErrorStyler};
use Tetthys\Input\Support\DefaultErrorStyler;
use Tetthys\Input\Components\{Text, CheckboxGroup, SelectAdvanced};

describe('ErrorStyler integration: class injection per error policy', function () {

    it('falls back to "is-invalid" when no styler is given', function () {
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
        $ctx = new InputContext(new ValueResolver(), $errors, styler: null);

        $html = (new Text($ctx, 'email', ['class' => 'form'], default: 'a@b.c'))->render();
        expect($html)->toContain('class="form is-invalid"');
    });

    it('uses DefaultErrorStyler to override the error class', function () {
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
        $styler = new DefaultErrorStyler('tw-err'); // custom class
        $ctx = new InputContext(new ValueResolver(), $errors, $styler);

        $html = (new Text($ctx, 'email', ['class' => 'form'], default: 'a@b.c'))->render();
        expect($html)->toContain('class="form tw-err"');
        expect($html)->not->toContain('is-invalid');
    });

    it('can return multiple classes (space-separated) and they are merged after base class', function () {
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'email';
            }
            public function get(string $name): array
            {
                return ['Invalid.', 'Too short.'];
            }
        };
        $styler = new class implements ErrorStyler {
            public function classFor(string $name, array $messages): ?string
            {
                return 'ring-2 ring-red-500 border-red-500';
            }
        };
        $ctx = new InputContext(new ValueResolver(), $errors, $styler);

        $html = (new Text($ctx, 'email', ['class' => 'form'], default: 'x'))->render();
        expect($html)->toContain('class="form ring-2 ring-red-500 border-red-500"');
    });

    it('returns null/empty => no extra class appended even when hasError()', function () {
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
        $styler = new class implements ErrorStyler {
            public function classFor(string $name, array $messages): ?string
            {
                return '';
            }
        };
        $ctx = new InputContext(new ValueResolver(), $errors, $styler);

        $html = (new Text($ctx, 'email', ['class' => 'base'], default: 'x'))->render();
        expect($html)->toContain('class="base"');
        expect($html)->not->toContain('is-invalid');
    });

    it('CheckboxGroup uses original logical key for ErrorStore, and applies styler per input', function () {
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
        $styler = new DefaultErrorStyler('group-err');
        $ctx = new InputContext(new ValueResolver(), $errors, $styler);

        $opts = ['x' => 'X', 'y' => 'Y', 'z' => 'Z'];
        $html = (new CheckboxGroup($ctx, 'roles', $opts, default: ['x']))->render();

        // Each input must carry class="group-err"
        expect(substr_count($html, 'class="group-err"'))->toBe(3);
        expect($html)->toContain('name="roles[]"');
    });

    it('SelectAdvanced merges base class with custom error class', function () {
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'c';
            }
            public function get(string $name): array
            {
                return ['Oops'];
            }
        };
        $styler = new DefaultErrorStyler('oops');
        $ctx = new InputContext(new ValueResolver(), $errors, $styler);

        $opts = ['a' => 'A'];
        $html = (new SelectAdvanced($ctx, 'c', $opts, ['class' => 'select'], default: 'a'))->render();

        expect($html)->toContain('<select');
        expect($html)->toContain('class="select oops"');
    });
});
