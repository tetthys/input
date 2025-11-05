<?php

use Tetthys\Input\Context\InputContext;
use Tetthys\Input\Value\ValueResolver;
use Tetthys\Input\Components\{Number, Range, Date, Time};

describe('Usage: number/date/time inputs', function () {
    it('Number renders value and numeric attrs', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Number($ctx, 'age', attrs: ['min' => 0, 'max' => 120, 'step' => 1], default: 34))->render();
        expect($html)->toContain('type="number"')
            ->toContain('value="34"')
            ->toContain('min="0"')->toContain('max="120"')->toContain('step="1"');
    });

    it('Range renders slider value', function () {
        $ctx = new InputContext(new ValueResolver());
        $html = (new Range($ctx, 'volume', default: 7))->render();
        expect($html)->toContain('type="range"')->toContain('value="7"');
    });

    it('Date and Time render ISO-like values', function () {
        $ctx = new InputContext(new ValueResolver());
        expect((new Date($ctx, 'report.date', default: '2025-11-05'))->render())
            ->toContain('type="date"')->toContain('value="2025-11-05"');
        expect((new Time($ctx, 'report.time', default: '13:45'))->render())
            ->toContain('type="time"')->toContain('value="13:45"');
    });
});
