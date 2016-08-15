<?php

namespace BarLocator\Client\Response\Interpreter;

class InterpreterFactory
{
    /**
     * Returns instance of radar search interpreter
     *
     * @return Radar
     */
    public function getRadarSearchInterpreter()
    {
        return new Radar();
    }

    /**
     * Returns instance of place detail interpreter
     *
     * @return Detail
     */
    public function getDetailInterpreter()
    {
        return new Detail();
    }
}