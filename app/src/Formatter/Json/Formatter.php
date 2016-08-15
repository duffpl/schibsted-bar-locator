<?php

namespace BarLocator\Formatter\Json;

use Symfony\Component\HttpFoundation\JsonResponse;

class Formatter implements \BarLocator\Formatter\Formatter
{
    public function format($data)
    {
        return new JsonResponse($data, 200, []);
    }
}