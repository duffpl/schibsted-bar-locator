<?php

namespace BarLocator\Controller;

use BarLocator\Client\GooglePlaces;
use BarLocator\Formatter\FormatterFactory;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


class Place
{
    /** @var GooglePlaces */
    protected $apiClient;

    /** @var FormatterFactory */
    protected $formatterFactory;

    /**
     * Place constructor.
     * @param GooglePlaces $apiClient
     */
    public function __construct(GooglePlaces $apiClient, FormatterFactory $formatterFactory)
    {
        $this->formatterFactory = $formatterFactory;
        $this->apiClient = $apiClient;
    }

    /**
     * Fetches radarsearch data using api client and returns formatted result
     *
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function radarAction(Request $request, Application $app)
    {
        $latitude = $request->get('latitude', null);
        $longitude = $request->get('longitude', null);
        if (isset($latitude, $longitude)) {
            $coords = new \BarLocator\Geo\Coordinates($latitude, $longitude);
        } else {
            $coords = $app['config.default_location'];
        }
        $result = $this->apiClient->findPlaces($coords);
        return self::formatResponse($result, $request);
    }

    /**
     * Fetches place detail data using api client and returns formatted result
     *
     * @param $placeId
     * @param Request $request
     * @return mixed
     */
    public function detailAction($placeId, Request $request)
    {
        /** @var GooglePlaces $client */
        $apiResult = $this->apiClient->getPlaceDetail($placeId);
        return self::formatResponse($apiResult, $request);
    }

    /**
     * Formats response using formatter created by factory
     *
     * @param $responseData
     * @param Request $request
     * @return mixed
     */
    protected function formatResponse($responseData, Request $request) {
        $formatType = $request->get('format', null);
        if ($formatType) {
            $formatter = $this->formatterFactory->getFormatterByType($formatType);
        } else {
            $formatter = $this->formatterFactory->getDefaultFormatter();
        }
        return $formatter->format($responseData);
    }

}