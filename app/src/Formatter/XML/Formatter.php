<?php

namespace BarLocator\Formatter\XML;

class Formatter implements \BarLocator\Formatter\Formatter
{
    public function format($data)
    {
        return new Response($data, 200, ['Content-Type' => 'application/xml']);
    }
}
