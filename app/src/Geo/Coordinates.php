<?php

namespace BarLocator\Geo;

class Coordinates
{
    public $latitude;
    public $longitude;

    /**
     * Location constructor.
     * @param $latitude
     * @param $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    function __toString()
    {
        return join(',', [$this->latitude, $this->longitude]);
    }


}