<?php

namespace BarLocator\Client\Response\Interpreter;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class BaseInterpreter implements Interpreter
{
    const STATUS_OK = 'OK';
    public function interpret(Response $response)
    {
        $json = \GuzzleHttp\json_decode($response->getBody());
        $status = $json->status;
        if ($status !== self::STATUS_OK) {
            $errorMessageTokens = [
                'Google Places API error',
                'API Status: ' . $json->status
            ];
            if (isset($json->error_message)) {
                $errorMessageTokens[] = 'API Error message: ' . $json->error_message;
            }
            throw new HttpException(400, join('. ', $errorMessageTokens));
        }
        return $json;
    }
}
