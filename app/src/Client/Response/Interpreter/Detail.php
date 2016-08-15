<?php
namespace BarLocator\Client\Response\Interpreter;

use BarLocator\Entity\Location;
use GuzzleHttp\Psr7\Response;

class Detail extends BaseInterpreter
{
    /**
     * @param Response $response
     * @return Location
     */
    public function interpret(Response $clientResponse)
    {
        $response = parent::interpret($clientResponse);
        $placeData = $response->result;
        $result = new Location();
        $result->name = $placeData->name;
        $result->phone = $placeData->formatted_phone_number ?? '';
        $result->address = $placeData->formatted_address;
        $result->website = $placeData->website ?? '';
        $result->rating = $placeData->rating ?? 0;
        return $result;
    }
}