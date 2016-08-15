<?php

namespace BarLocator\Entity;

use BarLocator\Geo\Coordinates;

class RadarLocation
{
    public $placeId;
    /** @var Coordinates */
    public $coordinates;

    /**
     * RadarLocation constructor.
     * @param $placeId
     * @param Coordinates $coordinates
     */
    public function __construct($placeId, Coordinates $coordinates)
    {
        $this->placeId = $placeId;
        $this->coordinates = $coordinates;
    }
}