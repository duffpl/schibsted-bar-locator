<?php
describe('Client\Request\RequestFactory', function () {
    /** @var \GuzzleHttp\Psr7\Uri $baseUri */
    $baseUri = null;
    $apiKey = 'MyAPIKey';
    /** @var \BarLocator\Client\Request\RequestFactory $factory */
    $factory = null;
    beforeEach(function () use (&$baseUri, &$factory, &$apiKey) {
        $baseUri = new \GuzzleHttp\Psr7\Uri('http://base.uri');
        $factory = new \BarLocator\Client\Request\RequestFactory($baseUri, $apiKey);
    });
    describe('Radar request', function () use (&$factory, &$apiKey, &$baseUri) {
        it('sets all required query parameters for radar request', function () use (&$factory, &$apiKey) {
            $coordinates = new \BarLocator\Geo\Coordinates(123,456);
            $request = $factory->getRadarRequest($coordinates);
            parse_str($request->getUri()->getQuery(), $queryParams);
            expect($queryParams)->toContainKeyWithValue('location', '123,456');
            expect($queryParams)->toContainKeyWithValue('type', 'bar');
            expect($queryParams)->toContainKeyWithValue('radius', '2000');
            expect($queryParams)->toContainKeyWithValue('key', $apiKey);
        });
        it('sets correct path for radar request', function () use (&$factory) {
            $request = $factory->getRadarRequest(new \BarLocator\Geo\Coordinates(1,2));
            $path = $request->getUri()->getPath();
            expect($path)->toEqual('/radarsearch/json');
        });
        it('sets correct host for radar request', function () use (&$factory, &$baseUri) {
            $request = $factory->getRadarRequest(new \BarLocator\Geo\Coordinates(1,2));
            $host = $request->getUri()->getHost();
            expect($host)->toEqual($baseUri->getHost());
        });
    });
    describe('Detail request', function () use (&$factory, &$apiKey, &$baseUri) {
        it('sets all required query parameters for detail request', function () use (&$factory, &$apiKey) {
            $placeId = 'SomeReallyNicePlaceID';
            $request = $factory->getPlaceDetailRequest($placeId);
            parse_str($request->getUri()->getQuery(), $queryParams);
            expect($queryParams)->toContainKeyWithValue('placeid', $placeId);
            expect($queryParams)->toContainKeyWithValue('key', $apiKey);
        });
        it('sets correct path for detail request', function () use (&$factory) {
            $request = $factory->getPlaceDetailRequest('123');
            $path = $request->getUri()->getPath();
            expect($path)->toEqual('/details/json');
        });
        it('sets correct host for detail request', function () use (&$factory, &$baseUri) {
            $radarRequest = $factory->getPlaceDetailRequest('123');
            $host = $radarRequest->getUri()->getHost();
            expect($host)->toEqual($baseUri->getHost());
        });
    });
});
