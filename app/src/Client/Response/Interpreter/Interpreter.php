<?php

namespace BarLocator\Client\Response\Interpreter;

use GuzzleHttp\Psr7\Response;

interface Interpreter
{
    public function interpret(Response $response);
}