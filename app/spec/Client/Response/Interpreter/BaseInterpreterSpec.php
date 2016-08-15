<?php
use Kahlan\Plugin\Stub;
use spec\Client\Response\Interpreter\ConcreteInterpreter;
use Symfony\Component\HttpKernel\Exception\HttpException;

describe('BarLocator\Client\Response\Interpreter\BaseInterpreter', function () {
    /** @var ConcreteInterpreter $interpreter */
    $interpreter = null;
    /** @var \GuzzleHttp\Psr7\Response $guzzleResponse */
    $guzzleResponse = null;
    beforeEach(function () use (&$interpreter, &$guzzleResponse) {
        $interpreter = new ConcreteInterpreter();
        $guzzleResponse = Stub::create(['extends' => \GuzzleHttp\Psr7\Response::class]);
    });
    it('throws exception if response has status other than OK', function () use (&$interpreter, &$guzzleResponse) {
        Stub::on($guzzleResponse)->method('getBody')->andReturn(json_encode(['status' => 'NOT OK. GO AWAY']));
        expect(function () use ($interpreter, $guzzleResponse) {
            $interpreter->interpret($guzzleResponse);
        })->toThrow(new HttpException(400, 'Google Places API error. API Status: NOT OK. GO AWAY'));
    });
    it('throws exception that includes API error_message if response has status other than OK and error_message has been sent', function () use (&$interpreter, &$guzzleResponse) {
        Stub::on($guzzleResponse)->method('getBody')->andReturn(json_encode(['status' => 'NOT OK. GO AWAY', 'error_message'=>'Abandon ship']));
        expect(function () use ($interpreter, $guzzleResponse) {
            $interpreter->interpret($guzzleResponse);
        })->toThrow(new HttpException(400, 'Google Places API error. API Status: NOT OK. GO AWAY. API Error message: Abandon ship'));
    });
    it('doesnt throw exception if response has status OK', function () use (&$interpreter, &$guzzleResponse) {
        Stub::on($guzzleResponse)->method('getBody')->andReturn(json_encode(['status' => 'OK']));
        expect(function () use ($interpreter, $guzzleResponse) {
            $interpreter->interpret($guzzleResponse);
        })->not->toThrow(new HttpException(400));
    });
});