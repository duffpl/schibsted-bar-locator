<?php
use BarLocator\Client\Response\Interpreter\Radar;
use Kahlan\Plugin\Stub;

describe('BarLocator\Client\Response\Interpreter\Radar', function () {
    /** @var Radar $interpreter */
    $interpreter = null;
    /** @var \GuzzleHttp\Psr7\Response $guzzleResponse */
    $guzzleResponse = null;
    $fixtureData = null;
    before(function () use (&$fixtureData) {
        $fixtureData = json_decode(file_get_contents(__DIR__ . '/fixtures/radar.fixture.json'));
    });
    beforeEach(function () use (&$interpreter, &$guzzleResponse) {
        $interpreter = new Radar();
        $guzzleResponse = Stub::create(['extends' => \GuzzleHttp\Psr7\Response::class]);
    });
    it('properly interprets json result from Google API', function () use (&$interpreter, &$guzzleResponse, &$fixtureData) {
        Stub::on($guzzleResponse)->method('getBody')->andReturn(json_encode($fixtureData));
        $result = $interpreter->interpret($guzzleResponse);
        expect($result)->toBeAnInstanceOf(\BarLocator\Entity\RadarLocationCollection::class);
        expect($result->items)->toHaveLength(2);
        expect($result->items[0]->placeId)->toEqual('Place1ID');
        expect($result->items[1]->placeId)->toEqual('Place2ID');
        expect((string)$result->items[0]->coordinates)->toEqual('11.1,22.2');
        expect((string)$result->items[1]->coordinates)->toEqual('33.3,44.4');
    });
});
