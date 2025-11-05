<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Contracts\ErrorStore;
use Tetthys\Input\Components\Checkbox;

function bareAttr(string $html, string $attr): bool
{
    return (bool)preg_match('/\s' . preg_quote($attr, '/') . '(?=\s|>)/', $html);
}

describe('Checkbox: equality, attributes, error class', function () {

    it('is checked when (string)value === (string)checkedValue', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Checkbox($ctx, 'agree', default: '1', checkedValue: '1'))->render();

        expect($html)->toContain('type="checkbox"')->toContain('value="1"');
        expect(bareAttr($html, 'checked'))->toBeTrue();
    });

    it('is not checked when they differ (even falsy vs truthy)', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Checkbox($ctx, 'agree', default: 0, checkedValue: '1'))->render();
        expect(bareAttr($html, 'checked'))->toBeFalse();
    });

    it('merges class and appends is-invalid on error', function () {
        $errors = new class implements ErrorStore {
            public function has(string $name): bool
            {
                return $name === 'agree';
            }
            public function get(string $name): array
            {
                return [];
            }
        };
        $ctx = new InputContext(new ValueResolver(), $errors);

        $html = (new Checkbox($ctx, 'agree', ['class' => 'form-check'], default: '1'))->render();
        expect($html)->toContain('class="form-check is-invalid"');
    });

    it('renders bare boolean attributes and escapes values', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Checkbox($ctx, 'x', ['required' => true, 'disabled' => false, 'data-note' => '"<&>'], default: 'yes', checkedValue: 'yes'))->render();

        expect(bareAttr($html, 'required'))->toBeTrue();
        expect($html)->not->toContain('disabled');
        expect($html)->toContain('data-note="&quot;&lt;&amp;&gt;"');
    });
});
