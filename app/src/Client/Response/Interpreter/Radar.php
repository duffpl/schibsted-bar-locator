<?php

namespace BarLocator\Client\Response\Interpreter;

use BarLocator\Entity\RadarLocation;
use BarLocator\Entity\RadarLocationCollection;
use BarLocator\Geo\Coordinates;
use GuzzleHttp\Psr7\Response;

class Radar extends BaseInterpreter
{
    /**
     * @param Response $clientResponse
     * @return RadarLocationCollection
     */
    public function interpret(Response $clientResponse)
    {
        $response = parent::interpret($clientResponse);

        $collection = new RadarLocationCollection();
        foreach ($response->results as $foundPlace) {
            $coordinates = new Coordinates(
                $foundPlace->geometry->location->lat,
                $foundPlace->geometry->location->lng
            );
            $collection->items[] = new RadarLocation($foundPlace->place_id, $coordinates);
        }
        return $collection;
    }
}