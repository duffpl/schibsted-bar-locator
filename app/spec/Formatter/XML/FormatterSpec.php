<?php
use BarLocator\Formatter\XML\Formatter;
use BarLocator\Formatter\XML\Response;
use Kahlan\Plugin\Stub;

describe('Formatter', function () {
    /** @var Formatter $formatter */
    $formatter = null;
    beforeEach(function () use (&$formatter) {
        $formatter = new Formatter();
    });
    it('returns XML response', function () use (&$formatter){
        Stub::on(Response::class)->method('__construct');
        expect($formatter->format(''))->toBeAnInstanceOf(Response::class);
    });
    it('sets correct content type on response', function () use (&$formatter){
        Stub::on(Response::class)->method('setContent');
        $response = $formatter->format('');
        expect($response->headers->get('Content-Type'))->toEqual('application/xml');

    });
});