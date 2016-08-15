<?php
describe('Formatter', function () {
    it('returns JSON response', function () {
        $formatter = new \BarLocator\Formatter\Json\Formatter();
        $data = new stdClass();
        expect($formatter->format($data))->toBeAnInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class);
    });
});