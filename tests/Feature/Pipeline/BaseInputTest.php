<?php
// tests/Feature/Pipeline/BaseInputTest.php

use Tetthys\Input\Components\BaseInput;
use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Contracts\ErrorStyler;
use Tetthys\Input\Value\ValueResolver;

/**
 * Minimal concrete input used only for tests.
 */
final class FakeTextInput extends BaseInput
{
    public function render(): string
    {
        $base  = $this->attrs['class'] ?? '';
        $err   = $this->errorClass();
        $class = trim($base . ($err ? ' ' . $err : ''));

        $attrs = array_merge($this->attrs, [
            'name'  => $this->name,
            'type'  => 'text',
            'class' => $class ?: null,
            'value' => (string)($this->value ?? ''),
        ]);

        return '<input' . $this->htmlAttrs($attrs) . ' />';
    }
}

describe('BaseInput: fallback error class', function () {
    it('no styler => uses "is-invalid" when field has error', function () {
        $resolver = new ValueResolver();
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'email';
            }
            public function get(string $name): array
            {
                return ['Required'];
            }
        };

        $ctx = new InputContext($resolver, errors: $errors);
        $html = (new FakeTextInput($ctx, 'email', ['class' => 'base']))->render();

        expect($html)->toContain('class="base is-invalid"');
    });

    it('no styler + bem_block => expands to block--is-invalid', function () {
        $resolver = new ValueResolver();
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return true;
            }
            public function get(string $name): array
            {
                return ['x'];
            }
        };

        $ctx = new InputContext($resolver, errors: $errors);
        $html = (new FakeTextInput($ctx, 'u', ['class' => 'base', 'bem_block' => 'input-text']))->render();

        expect($html)->toContain('class="base input-text--is-invalid"');
    });
});

describe('BaseInput: styler policy', function () {
    it('styler returns empty => no extra class appended', function () {
        $resolver = new ValueResolver();
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return true;
            }
            public function get(string $name): array
            {
                return ['x'];
            }
        };
        $styler = new class implements ErrorStyler {
            public function classFor(string $name, array $messages): ?string
            {
                return '';
            }
        };

        $ctx = new InputContext($resolver, errors: $errors, styler: $styler);
        $html = (new FakeTextInput($ctx, 'email', ['class' => 'base']))->render();

        expect($html)->toContain('class="base"');
        expect($html)->not->toContain('is-invalid');
    });

    it('styler single token + bem_block => expands to block--token', function () {
        $resolver = new ValueResolver();
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return true;
            }
            public function get(string $name): array
            {
                return ['x'];
            }
        };
        $styler = new class implements ErrorStyler {
            public function classFor(string $name, array $messages): ?string
            {
                return 'error';
            }
        };

        $ctx = new InputContext($resolver, errors: $errors, styler: $styler);
        $html = (new FakeTextInput($ctx, 'f', ['class' => 'base', 'bem_block' => 'input-text']))->render();

        expect($html)->toContain('class="base input-text--error"');
    });

    it('styler multi-classes => pass-through (no BEM expansion)', function () {
        $resolver = new ValueResolver();
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return true;
            }
            public function get(string $name): array
            {
                return ['x'];
            }
        };
        $styler = new class implements ErrorStyler {
            public function classFor(string $name, array $messages): ?string
            {
                return 'ring-1 border-red-500';
            }
        };

        $ctx = new InputContext($resolver, errors: $errors, styler: $styler);
        $html = (new FakeTextInput($ctx, 'f', ['class' => 'base', 'bem_block' => 'input-text']))->render();

        expect($html)->toContain('class="base ring-1 border-red-500"');
        expect($html)->not->toContain('input-text--');
    });
});

describe('BaseInput: htmlAttrs behavior', function () {
    it('true => bare attr, false/null omitted, values escaped', function () {
        $ctx = new InputContext(new ValueResolver());

        $html = (new FakeTextInput($ctx, 'title', attrs: [
            'required' => true,
            'disabled' => false,
            'data-x'   => null,
            'title'    => 'A "B"&<C>',
        ], default: 'v'))->render();

        expect($html)->toContain(' required'); // bare attribute
        expect($html)->not->toContain('disabled'); // omitted
        expect($html)->not->toContain('data-x='); // omitted
        expect($html)->toContain('title="A &quot;B&quot;&amp;&lt;C&gt;"'); // escaped
        expect($html)->toContain('value="v"');
    });

    it('merges attrs with extra and includes name attr', function () {
        $ctx = new InputContext(new ValueResolver());

        $html = (new FakeTextInput($ctx, 'x', attrs: ['data-a' => '1'], default: 'z'))->render();

        expect($html)->toContain(' data-a="1"');
        expect($html)->toContain(' name="x"');
    });
});

describe('BaseInput: hasError()/errors() contract', function () {
    it('hasError=false when no store or no entry', function () {
        $ctx1 = new InputContext(new ValueResolver()); // no error store
        $ctx2 = new InputContext(new ValueResolver(), errors: new class implements ErrorStore {
            public function has(string $name): bool
            {
                return false;
            }
            public function get(string $name): array
            {
                return [];
            }
        });

        expect((new FakeTextInput($ctx1, 'a'))->hasError())->toBeFalse();
        expect((new FakeTextInput($ctx2, 'a'))->hasError())->toBeFalse();
        expect((new FakeTextInput($ctx2, 'a'))->errors())->toBeArray()->toBe([]);
    });

    it('hasError=true and errors() returns list', function () {
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'email';
            }
            public function get(string $name): array
            {
                return $name === 'email' ? ['Invalid', 'Too short'] : [];
            }
        };

        $ctx = new InputContext(new ValueResolver(), errors: $errors);
        $c = new FakeTextInput($ctx, 'email');

        expect($c->hasError())->toBeTrue();
        expect($c->errors())->toBe(['Invalid', 'Too short']);
    });
});
