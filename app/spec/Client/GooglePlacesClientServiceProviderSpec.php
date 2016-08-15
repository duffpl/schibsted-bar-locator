<?php
use BarLocator\Client\GooglePlaces;
use BarLocator\Client\GooglePlacesClientServiceProvider;
use BarLocator\Client\Request\RequestFactory;
use BarLocator\Client\Response\Interpreter\InterpreterFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Kahlan\Plugin\Stub;

describe('BarLocator\Client\GooglePlacesClientServiceProvider', function () {
    /** @var \Silex\Application $app */
    $app = null;
    beforeEach(function () use (&$app) {
        $app = new Silex\Application();
        $app->register(new GooglePlacesClientServiceProvider());
    });
    it('registers guzzle client service', function () use (&$app) {
        expect($app['guzzle'])->toBeAnInstanceOf(Client::class);
    });
    it('registers responseInterpreterFactory', function () use (&$app) {
        expect($app['responseInterpreterFactory'])->toBeAnInstanceOf(InterpreterFactory::class);
    });
    it('registers requestFactory with its dependecies', function () use (&$app) {
        $uri = $app['config.baseuri'] = new Uri('http://some.uri');
        $configKey = $app['config.apikey'] = 'api key';
        Stub::on(RequestFactory::class)->method(
            '__construct',
            function($passedUri, $passedApiKey) use (&$uri, &$configKey) {
                expect($passedUri)->toBe($uri);
                expect($passedApiKey)->toBe($configKey);
            }
        );
        expect($app['requestFactory'])->toBeAnInstanceOf(RequestFactory::class);
    });
    it('registers api client with its dependecies', function () use (&$app) {
        $guzzle = new Client();
        $app['guzzle'] = function () use ($guzzle) { return $guzzle; };

        $interpreterFactory = Stub::create(['extends' => InterpreterFactory::class, 'magicMethods' => true]);
        $app['responseInterpreterFactory'] = function() use ($interpreterFactory) { return $interpreterFactory; };

        $requestFactory = Stub::create(['extends' => RequestFactory::class, 'magicMethods' => true]);
        $app['requestFactory'] = function () use ($requestFactory) {return $requestFactory;};

        Stub::on(GooglePlaces::class)->method(
            '__construct',
            function($passedGuzzle, $passedInterpreterFactory, $passedRequestFactory) use (&$guzzle, &$interpreterFactory, &$requestFactory) {
                expect($passedGuzzle)->toBe($guzzle);
                expect($passedInterpreterFactory)->toBe($interpreterFactory);
                expect($passedRequestFactory)->toBe($requestFactory);
            }
        );
        expect($app['client'])->toBeAnInstanceOf(GooglePlaces::class);
    });
});