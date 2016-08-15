<?php

namespace BarLocator\Client;

use BarLocator\Client\Request\RequestFactory;
use BarLocator\Client\Response\Interpreter\InterpreterFactory;
use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class GooglePlacesClientServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['guzzle'] = function () {
            return new Client();
        };

        $app['responseInterpreterFactory'] = function () {
            return new InterpreterFactory();
        };

        $app['requestFactory'] = function ($app) {
            return new RequestFactory($app['config.baseuri'], $app['config.apikey']);
        };

        $app['client'] = function ($app) {
            return new GooglePlaces($app['guzzle'], $app['responseInterpreterFactory'], $app['requestFactory']);
        };
    }
}