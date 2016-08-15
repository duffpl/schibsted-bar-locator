<?php
describe('XML Response', function () {
    it('Uses Sabre XML writer for serializing output', function () {
        $content = new stdClass();
        \Kahlan\Plugin\Stub::on(\Sabre\Xml\Service::class)->method('writeValueObject')->andReturn('xml content');
        $response = new \BarLocator\Formatter\XML\Response($content, 200, []);
        expect($response->getContent())->toEqual('xml content');
    });
});