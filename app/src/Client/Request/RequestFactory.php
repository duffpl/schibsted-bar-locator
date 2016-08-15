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

    /**
     * Creates request object for radarsearch call
     *
     * @param Coordinates $location
     * @return Request
     */
    public function getRadarRequest(Coordinates $location)
    {
        $queryParameters = [
            'location' => (string)$location,
            'type' => 'bar',
            'radius' => 2000
        ];
        return new Request('GET', $this->getUri('radarsearch', $queryParameters));
    }

    /**
     * Creates request object for place detail call
     *
     * @param $placeId
     * @return Request
     */
    public function getPlaceDetailRequest($placeId)
    {
        $queryParameters = [
            'placeid' => $placeId
        ];
        return new Request('GET', $this->getUri('details', $queryParameters));
    }

    /**
     * Returns modified URI object with appended path and additional query parameters
     *
     * @param $path
     * @param array $queryParameters
     * @return Uri
     */
    private function getUri($path, $queryParameters = [])
    {
        $baseUri = $this->getBaseUri();
        $uri = Manipulator::appendPath($baseUri, join('/', [$path, 'json']));
        return Manipulator::appendQueryParameters($uri, $queryParameters);
    }

    /**
     * Returns base URI object that contains required query parameters
     *
     * @return Uri
     */
    private function getBaseUri()
    {
        return (new Uri($this->baseUri))
            ->withQuery(http_build_query($this->getRequiredParameters()));
    }

    /**
     * Returns list of query parameters required by all requests
     *
     * @return array
     */
    private function getRequiredParameters()
    {
        return [
            'key' => $this->apiKey
        ];
    }
}