<?php

namespace BarLocator\Client\Request;

use BarLocator\Client\Uri\Manipulator;
use BarLocator\Geo\Coordinates;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class RequestFactory
{
    private $apiKey;
    private $baseUri;

    /**
     * Factory constructor.
     * @param $baseUri
     * @param $apiKey
     */
    public function __construct($baseUri, $apiKey)
    {
        $this->baseUri = $baseUri;
        $this->apiKey = $apiKey;
    }

    public function getRadarRequest(Coordinates $location)
    {
        $queryParameters = [
            'location' => (string)$location,
            'type' => 'bar',
            'radius' => 2000
        ];
        return new Request('GET', $this->getUri('radarsearch', $queryParameters));
    }

    public function getPlaceDetailRequest($placeId)
    {
        $queryParameters = [
            'placeid' => $placeId
        ];
        return new Request('GET', $this->getUri('details', $queryParameters));
    }

    private function getUri($path, $queryParameters = [])
    {
        $baseUri = $this->getBaseUri();
        $uri = Manipulator::appendPath($baseUri, join('/', [$path, 'json']));
        return Manipulator::appendQueryParameters($uri, $queryParameters);
    }

    private function getBaseUri()
    {
        return (new Uri($this->baseUri))
            ->withQuery(http_build_query($this->getRequiredParameters()));
    }

    private function getRequiredParameters()
    {
        return [
            'key' => $this->apiKey
        ];
    }

}