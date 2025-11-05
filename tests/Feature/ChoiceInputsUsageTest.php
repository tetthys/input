<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\{Radio, CheckboxGroup, Select, SelectAdvanced};

describe('Usage: choice inputs', function () {
    it('Radio checks when value matches radioValue', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Radio($ctx, 'role', default: 'admin', radioValue: 'admin'))->render();
        expect($html)->toContain('type="radio"')->toContain('value="admin"')->toContain('checked');
    });

    it('CheckboxGroup supports multiple values and [] name', function () {
        $ctx = new InputContext(new ValueResolver());
        $options = ['a' => 'Alpha', 'b' => 'Beta', 'g' => 'Gamma'];
        $html = (new CheckboxGroup($ctx, 'tags', $options, default: ['b', 'g']))->render();
        expect($html)->toContain('name="tags[]"')
            ->toContain('value="b"')->toContain('checked')
            ->toContain('value="g"')->toContain('checked');
    });

    it('Select (simple) selects current value', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Select($ctx, 'country', ['kr' => 'Korea', 'jp' => 'Japan'], default: 'kr'))->render();
        expect($html)->toContain('<option value="kr" selected>')->toContain('>Korea<');
    });

    it('SelectAdvanced supports optgroups and multiple', function () {
        $ctx = new InputContext(new ValueResolver());
        $opts = [
            ['label' => 'EU',   'options' => ['de' => 'Germany', 'fr' => 'France']],
            ['label' => 'APAC', 'options' => ['kr' => 'Korea', 'jp' => 'Japan']],
            'us' => 'USA',
        ];
        $html = (new SelectAdvanced($ctx, 'countries', $opts, attrs: ['multiple' => true], default: ['fr', 'kr']))->render();
        expect($html)->toContain('<optgroup label="EU">')
            ->toContain('value="fr" selected')
            ->toContain('<optgroup label="APAC">')
            ->toContain('value="kr" selected')
            ->toContain('value="us"');
    });
});
