<?php
use Kahlan\Plugin\Stub;

describe('FormatterFactoryServiceProvider', function () {
    $application = null;
    beforeEach(function () use (&$application) {
        $application = new \Silex\Application();
        $application->register(new \BarLocator\Formatter\FormatterFactoryServiceProvider(), [
            'formatters' => [
                'json' => \BarLocator\Formatter\Json\Formatter::class
            ]
        ]);
    });
    it('registers FormatterFactory', function () use (&$application) {
        Stub::on(\BarLocator\Formatter\FormatterFactory::class)->method('__construct')->andReturn(true);
        expect($application['formatterFactory'])->toBeAnInstanceOf(\BarLocator\Formatter\FormatterFactory::class);
    });
    it('registers formatters passed in configuration', function () use (&$application) {
        Stub::on(\BarLocator\Formatter\FormatterFactory::class)->method('__construct')->andReturn(true);
        $formatters = [
            'some-formatter' => 'SomeFormatterClass',
            'some-other-formatter' => 'SomeOtherFormatterClass'
        ];
        $application['formatters'] = $formatters;
        expect($application['formatterFactory']->getRegisteredFormatters())->toEqual($formatters);
    });

});