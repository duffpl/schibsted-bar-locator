<?php
use BarLocator\Client\Response\Interpreter\Detail;
use BarLocator\Client\Response\Interpreter\InterpreterFactory;
use BarLocator\Client\Response\Interpreter\Radar;

describe('BarLocator\Client\Response\Interpreter\InterpreterFactory', function () {
    /** @var InterpreterFactory $factory */
    $factory = null;
    beforeEach(function () use (&$factory) {
        $factory = new InterpreterFactory();
    });
    it('creates Detail interpreter', function () use (&$factory) {
        expect($factory->getDetailInterpreter())->toBeAnInstanceOf(Detail::class);
    });
    it('creates RadarSearch interpreter', function () use (&$factory) {
        expect($factory->getRadarSearchInterpreter())->toBeAnInstanceOf(Radar::class);
    });
});
