<?php

namespace spec\Client;

use BarLocator\Client\GooglePlaces;
use BarLocator\Client\Request\RequestFactory;
use BarLocator\Client\Response\Interpreter\Interpreter;
use BarLocator\Client\Response\Interpreter\InterpreterFactory;
use BarLocator\Geo\Coordinates;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Kahlan\Plugin\Stub;
use GuzzleHttp\Psr7\Request;
describe('BarLocator\Client\GooglePlaces', function () {
    /** @var GooglePlaces $client */
    $client = null;
    /** @var Client $guzzle */
    $guzzle = null;
    /** @var InterpreterFactory $interpreterFactory */
    $interpreterFactory = null;
    /** @var RequestFactory $requestFactory */
    $requestFactory = null;
    beforeEach(function () use (&$client, &$guzzle, &$interpreterFactory, &$requestFactory) {
        $guzzle = Stub::create([
            'extends' => Client::class,
            'magicMethods' => true
        ]);
        Stub::on($guzzle)->method('send')->andReturn(new Response());
        $interpreterFactory = Stub::create([
            'extends' => InterpreterFactory::class,
            'magicMethods' => true
        ]);
        Stub::on($interpreterFactory)->methods([
            'getRadarSearchInterpreter' => [Stub::create(['implelements' => [Interpreter::class]])],
            'getDetailInterpreter' => [Stub::create(['implelements' => [Interpreter::class]])]
        ]);
        $requestFactory = Stub::create([
            'extends' => RequestFactory::class,
            'magicMethods' => true
        ]);
        Stub::on($requestFactory)->methods([
            'getRadarReqiest' => [true],
            'getDetailRequest' => [true]
        ]);
        $client = new GooglePlaces($guzzle, $interpreterFactory, $requestFactory);
    });
    describe('Radar searching', function () use (&$client, &$guzzle, &$interpreterFactory, &$requestFactory) {
        it('uses request factory with passed location to create radar search request', function () use (&$client, &$requestFactory) {
            $location = new Coordinates(1,2);
            expect($requestFactory)->toReceive('getRadarRequest')->with($location);
            $client->findPlaces($location);
        });
        it('uses guzzle to send radar request', function () use (&$client, &$requestFactory, &$guzzle) {
            $request = Stub::create(['extends' => Request::class, 'magicMethods' => true]);
            Stub::on($requestFactory)->method('getRadarRequest')->andReturn($request);
            expect($guzzle)->toReceive('send')->with($request);
            $client->findPlaces(new Coordinates(0,0));
        });

        it('uses interpreter factory to get interpreter for response', function () use (&$client, &$requestFactory, &$interpreterFactory, &$guzzle) {
            $response = new Response();
            $interpreter = Stub::create(['implements' => [Interpreter::class]]);
            Stub::on($guzzle)->method('send')->andReturn($response);
            expect($interpreterFactory)->toReceive('getRadarSearchInterpreter');
            Stub::on($interpreterFactory)->method('getRadarSearchInterpreter')->andReturn($interpreter);
            expect($interpreter)->toReceive('interpret')->with($response);
            Stub::on($interpreter)->method('interpret')->andReturn('Interpreted response');
            $result = $client->findPlaces(new Coordinates(1,1));
            expect($result)->toEqual('Interpreted response');
        });
    });
    describe('Place detail', function () use (&$client, &$guzzle, &$interpreterFactory, &$requestFactory) {
        it('uses request factory with passed place id to create detail request', function () use (&$client, &$requestFactory) {
            $placeId = 'My Place ID';
            expect($requestFactory)->toReceive('getPlaceDetailRequest')->with($placeId);
            $client->getPlaceDetail($placeId);
        });
        it('uses guzzle to send detail request', function () use (&$client, &$requestFactory, &$guzzle) {
            $request = Stub::create(['extends' => Request::class, 'magicMethods' => true]);
            Stub::on($requestFactory)->method('getPlaceDetailRequest')->andReturn($request);
            expect($guzzle)->toReceive('send')->with($request);
            $client->getPlaceDetail('123');
        });

        it('uses interpreter factory to get interpreter for response', function () use (&$client, &$requestFactory, &$interpreterFactory, &$guzzle) {
            $response = new Response();
            $interpreter = Stub::create(['implements' => [Interpreter::class]]);
            Stub::on($guzzle)->method('send')->andReturn($response);
            expect($interpreterFactory)->toReceive('getDetailInterpreter');
            Stub::on($interpreterFactory)->method('getDetailInterpreter')->andReturn($interpreter);
            expect($interpreter)->toReceive('interpret')->with($response);
            Stub::on($interpreter)->method('interpret')->andReturn('Interpreted detail response');
            $result = $client->getPlaceDetail('123');
            expect($result)->toEqual('Interpreted detail response');
        });
    });
});
