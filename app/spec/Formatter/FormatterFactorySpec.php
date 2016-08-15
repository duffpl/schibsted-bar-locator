<?php
use BarLocator\Formatter\FormatterFactory;
use Kahlan\Plugin\Stub;

describe('BarLocator\Formatter\FormatterFactory', function () {
    /** @var FormatterFactory $factory */
    $factory = null;
    it('registers formatter classes as map by type and creates new instances based on that map', function () {
        $factory = new FormatterFactory('default');
        $formatterClass = Stub::classname(['implements' => [\BarLocator\Formatter\Formatter::class]]);
        $factory->registerFormatter('customFormatter', $formatterClass);
        expect($factory->getFormatterByType('customFormatter'))->toBeAnInstanceOf($formatterClass);
    });
    it('uses formatter type passed in constructor to create default formatter', function () {
        $formatterClass = Stub::classname(['implements' => [\BarLocator\Formatter\Formatter::class]]);
        $factory = new FormatterFactory('defaultFormatter');
        $factory->registerFormatter('defaultFormatter', $formatterClass);
        expect($factory->getDefaultFormatter())->toBeAnInstanceOf($formatterClass);
    });
    it('throws exception if formatter type cannot be located in map', function () {
        $factory = new FormatterFactory('default');
        expect(function () use ($factory) {
            $factory->getFormatterByType('bogus');
        })->toThrow(new \Exception('Formatter of type "bogus" not found.'));
    });
});