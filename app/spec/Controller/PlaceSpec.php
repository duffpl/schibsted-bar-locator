<?php
use BarLocator\Client\GooglePlaces;
use BarLocator\Controller\Place;
use BarLocator\Formatter\FormatterFactory;
use Kahlan\Arg;
use Kahlan\Plugin\Stub;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

describe('Place Controller', function () {
    /** @var Place $controller */
    $controller = null;
    /** @var GooglePlaces $apiClient */
    $apiClient = null;
    /** @var FormatterFactory $formatterFactory */
    $formatterFactory = null;
    /** @var Request $request */
    $request = null;
    /** @var Application $application */
    $application = null;

    beforeEach(function () use (&$controller, &$apiClient, &$formatterFactory, &$request, &$application) {
        $apiClient = Stub::create(['extends' => GooglePlaces::class, 'magicMethods' => true]);
        Stub::on($apiClient)->methods([
            'findPlaces' => [true],
            'getPlaceDetail' => [true]
        ]);
        $formatter = Stub::create(['implements' => [\BarLocator\Formatter\Formatter::class]]);
        $formatterFactory = Stub::create(['extends' => FormatterFactory::class, 'magicMethods' => true]);
        Stub::on($formatterFactory)->methods([
            'getDefaultFormatter' => [$formatter],
            'getFormatterByType' => [$formatter]
        ]);
        $application = Stub::create(['extends' => Application::class, ['magicMethods' => true]]);
        $application['config.default_location'] = new \BarLocator\Geo\Coordinates(3,3);
        $controller = new Place($apiClient, $formatterFactory);
    });
    describe('Radar action', function () use (&$controller, &$apiClient, &$formatterFactory, &$application) {
        it('uses coordinates from request to perform searches using api client', function () use (&$controller, &$apiClient, &$application) {
            $request = new Request(['latitude' => 1, 'longitude' => 2]);
            expect($apiClient)->toReceive('findPlaces')->with(Arg::toMatch(function ($coords) {
                return $coords->latitude == 1 && $coords->longitude == 2;
            }));
            $controller->radarAction($request, $application);
        });
        it('uses default application coordinates if latitude and/or longitude were not in query', function () use (&$controller, &$apiClient, &$application) {
            $defaultLocation = new \BarLocator\Geo\Coordinates(66,66);
            $application['config.default_location'] = $defaultLocation;
            expect($apiClient)->toReceive('findPlaces')->with($defaultLocation)->times(3);
            $controller->radarAction(new Request(['latitude' => 1]), $application);
            $controller->radarAction(new Request(['longitude' => 1]), $application);
            $controller->radarAction(new Request([]), $application);
        });
        it('uses default formatter if format was not specified in request', function () use (&$controller, &$formatterFactory, &$application) {
            $controller->radarAction(new Request([]), $application);
            expect($formatterFactory)->toReceive('getDefaultFormatter');
        });
        it('uses formatter factory to create formatter specified in request', function () use (&$controller, &$formatterFactory, &$application) {
            $controller->radarAction(new Request(['format' => 'some-format']), $application);
            expect($formatterFactory)->toReceive('getFormatterByType')->with('some-format');
        });
        it('uses formatter returned by factory to format client response', function () use (&$controller, &$formatterFactory, &$application, &$apiClient) {
            $formatter = Stub::create(['implements' => [\BarLocator\Formatter\Formatter::class]]);
            Stub::on($formatterFactory)->method('getDefaultFormatter')->andReturn($formatter);
            $clientResponse = new stdClass();
            Stub::on($apiClient)->method('findPlaces')->andReturn($clientResponse);
            expect($formatter)->toReceive('format')->with($clientResponse);
            $formatterResponse = new stdClass();
            Stub::on($formatter)->method('format')->andReturn($formatterResponse);
            expect($controller->radarAction(new Request(), $application))->toBe($formatterResponse);
        });
    });
    describe('Detail action', function () use (&$controller, &$apiClient, &$formatterFactory, &$application) {
        it('uses passed place id to perform detail request using api client', function () use (&$controller, &$apiClient, &$application) {
            $placeId = 123;
            expect($apiClient)->toReceive('getPlaceDetail')->with($placeId);
            $controller->detailAction($placeId, new Request());
        });
        it('uses default formatter if format was not specified in request', function () use (&$controller, &$formatterFactory, &$application) {
            $controller->detailAction(1, new Request());
            expect($formatterFactory)->toReceive('getDefaultFormatter');
        });
        it('uses formatter factory to create formatter specified in request', function () use (&$controller, &$formatterFactory, &$application) {
            $controller->detailAction(1, new Request(['format' => 'some-format']));
            expect($formatterFactory)->toReceive('getFormatterByType')->with('some-format');
        });
        it('uses formatter returned by factory to format client response', function () use (&$controller, &$formatterFactory, &$application, &$apiClient) {
            $formatter = Stub::create(['implements' => [\BarLocator\Formatter\Formatter::class]]);
            Stub::on($formatterFactory)->method('getDefaultFormatter')->andReturn($formatter);
            $clientResponse = new stdClass();
            Stub::on($apiClient)->method('getPlaceDetail')->andReturn($clientResponse);
            expect($formatter)->toReceive('format')->with($clientResponse);
            $formatterResponse = new stdClass();
            Stub::on($formatter)->method('format')->andReturn($formatterResponse);
            expect($controller->detailAction(1, new Request()))->toBe($formatterResponse);
        });
    });
});
