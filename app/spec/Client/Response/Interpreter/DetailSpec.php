<?php
use BarLocator\Client\Response\Interpreter\Detail;
use Kahlan\Plugin\Stub;

describe('BarLocator\Client\Response\Interpreter\Detail', function () {
    /** @var Detail $interpreter */
    $interpreter = null;
    /** @var \GuzzleHttp\Psr7\Response $guzzleResponse */
    $guzzleResponse = null;
    $fixtureData = null;

    before(function () use (&$fixtureData) {
        $fixtureData = json_decode(file_get_contents(__DIR__ . '/fixtures/detail.fixture.json'));
    });

    beforeEach(function () use (&$interpreter, &$guzzleResponse) {
        $interpreter = new Detail();
        $guzzleResponse = Stub::create(['extends' => \GuzzleHttp\Psr7\Response::class]);
    });

    it('properly interprets json result from Google API', function () use (&$interpreter, &$guzzleResponse, &$fixtureData) {
        Stub::on($guzzleResponse)->method('getBody')->andReturn(json_encode($fixtureData));
        $result = $interpreter->interpret($guzzleResponse);
        expect($result)->toBeAnInstanceOf(\BarLocator\Entity\Location::class);
        expect($result->address)->toEqual('Podmurze 2, 80-835 Gdańsk, Polska');
        expect($result->name)->toEqual('Cafe Szafa');
        expect($result->rating)->toEqual(4.2);
        expect($result->phone)->toEqual('512 853 681');
        expect($result->website)->toEqual('http://www.cafeszafa.pl/');
    });
    it('sets rating to 0 if rating field wasnt present in response', function () use (&$interpreter, &$guzzleResponse, &$fixtureData) {
        $modifiedData = clone($fixtureData);
        unset($modifiedData->result->rating);
        Stub::on($guzzleResponse)->method('getBody')->andReturn(json_encode($modifiedData));
        $result = $interpreter->interpret($guzzleResponse);
        expect($result->rating)->toEqual(0);
    });

    it('sets website field to empty string if website field wasnt present in response', function () use (&$interpreter, &$guzzleResponse, &$fixtureData) {
        $modifiedData = clone($fixtureData);
        unset($modifiedData->result->website);
        Stub::on($guzzleResponse)->method('getBody')->andReturn(json_encode($modifiedData));
        $result = $interpreter->interpret($guzzleResponse);
        expect($result->website)->toEqual('');
    });

    it('sets phone number field to empty string if formatted_phone_number field wasnt present in response', function () use (&$interpreter, &$guzzleResponse, &$fixtureData) {
        $modifiedData = clone($fixtureData);
        unset($modifiedData->result->formatted_phone_number);
        Stub::on($guzzleResponse)->method('getBody')->andReturn(json_encode($modifiedData));
        $result = $interpreter->interpret($guzzleResponse);
        expect($result->phone)->toEqual('');
    });
});