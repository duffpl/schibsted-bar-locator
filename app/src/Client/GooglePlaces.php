<?php

namespace BarLocator\Client;

use BarLocator\Client\Request\RequestFactory;
use BarLocator\Client\Response\Interpreter\InterpreterFactory;
use BarLocator\Entity\Location;
use BarLocator\Geo\Coordinates;
use GuzzleHttp\Client;

class GooglePlaces
{
    /** @var Client */
    private $guzzle;
    /** @var Coordinates */
    private $location;
    /** @var InterpreterFactory */
    private $responseInterpreterFactory;
    /** @var RequestFactory */
    private $requestFactory;

    /**
     * GooglePlaces constructor.
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle, InterpreterFactory $responseInterpreterFactory, RequestFactory $requestFactory)
    {
        $this->guzzle = $guzzle;
        $this->requestFactory = $requestFactory;
        $this->responseInterpreterFactory = $responseInterpreterFactory;
    }

    /**
     * Returns interpreted search results from radarsearch api call
     *
     * @param Coordinates $location
     * @return \BarLocator\Entity\RadarLocationCollection
     */
    public function findPlaces(Coordinates $location)
    {
        $request = $this->requestFactory->getRadarRequest($location);
        $response = $this->guzzle->send($request);
        $interpreter = $this->responseInterpreterFactory->getRadarSearchInterpreter();
        return $interpreter->interpret($response);
    }

    /**
     * Returns interpreted search results from place detail api call
     *
     * @param $placeId
     * @return Location
     */
    public function getPlaceDetail($placeId)
    {
        $request = $this->requestFactory->getPlaceDetailRequest($placeId);
        $response = $this->guzzle->send($request);
        $interpreter = $this->responseInterpreterFactory->getDetailInterpreter();
        return $interpreter->interpret($response);
    }
}
