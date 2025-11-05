<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\{Text, Textarea, Password, Hidden, File};

describe('Usage: text-like inputs', function () {
    it('Text renders value from resolver', function () {
        $resolver = (new ValueResolver());
        $ctx = new InputContext($resolver);

        $html = (new Text($ctx, 'user.name', default: 'Alice'))->render();
        expect($html)->toContain('type="text"')->toContain('value="Alice"');
    });

    it('Textarea renders body text', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Textarea($ctx, 'user.bio', default: "hello\nworld"))->render();
        expect($html)->toContain('<textarea')->toContain('hello')->toContain('world');
    });

    it('Password does not render value unless prefill=true', function () {
        $ctx = new InputContext(new ValueResolver());

        $html1 = (new Password($ctx, 'user.password', default: 'secret'))->render();
        $html2 = (new Password($ctx, 'user.password', attrs: ['prefill' => true], default: 'secret'))->render();

        expect($html1)->toContain('type="password"')->not->toContain('value="secret"');
        expect($html2)->toContain('type="password"')->toContain('value="secret"');
    });

    it('Hidden renders value', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Hidden($ctx, 'token', default: 'abc123'))->render();
        expect($html)->toContain('type="hidden"')->toContain('value="abc123"');
    });

    it('File never renders value, respects attrs', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new File($ctx, 'avatar', attrs: ['accept' => '.png,.jpg', 'multiple' => true]))->render();
        expect($html)->toContain('type="file"')
            ->toContain('accept=".png,.jpg"')
            ->toContain('multiple')
            ->not->toContain('value=');
    });
});
