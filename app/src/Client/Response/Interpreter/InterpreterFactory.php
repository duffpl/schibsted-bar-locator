<?php

namespace BarLocator\Client\Response\Interpreter;

class InterpreterFactory
{
    public function getRadarSearchInterpreter()
    {
        return new Radar();
    }

    public function getDetailInterpreter()
    {
        return new Detail();
    }
}