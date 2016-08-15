<?php
describe('Geo', function () {
    it('casts its latitude and longitude as comma separated value', function () {
        $coordinates = new \BarLocator\Geo\Coordinates(12.5, 33.22);
        expect((string)$coordinates)->toEqual('12.5,33.22');
    });
});
