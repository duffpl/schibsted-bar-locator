<?php

namespace BarLocator\Formatter\XML;

use BarLocator\Entity\Location;
use BarLocator\Entity\RadarLocation;
use BarLocator\Entity\RadarLocationCollection;
use BarLocator\Geo\Coordinates;
use Sabre\Xml\Service;

class Response extends \Symfony\Component\HttpFoundation\Response
{
    public function __construct($content, $status, array $headers)
    {
        parent::__construct($content, $status, $headers);
    }

    public function setContent($content)
    {
        $service = new Service();
        $ns = '{http://www.w3.org/2005/Atom}';
        $service->namespaceMap['http://www.w3.org/2005/Atom'] = '';
        $service->mapValueObject('{http://www.w3.org/2005/Atom}location', Location::class);
        $service->mapValueObject('{http://www.w3.org/2005/Atom}locations', RadarLocationCollection::class);
        $service->mapValueObject('{http://www.w3.org/2005/Atom}radarLocation', RadarLocation::class);
        $service->mapValueObject('{http://www.w3.org/2005/Atom}coordinates', Coordinates::class);
        $output = $service->writeValueObject($content);
        $this->content = $output;
    }
}