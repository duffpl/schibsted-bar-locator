<?php
describe('BarLocator/Uri/Manipulator', function () {
    it('Properly appends paths to URI', function () {
        $uri = new \GuzzleHttp\Psr7\Uri('http://some.uri/with/path');
        $modifiedUri = \BarLocator\Client\Uri\Manipulator::appendPath($uri, 'appended');
        expect($modifiedUri->getPath())->toEqual('/with/path/appended');
        $furtherModifiedUri = \BarLocator\Client\Uri\Manipulator::appendPath($modifiedUri, 'and/some/extra');
        expect($furtherModifiedUri->getPath())->toEqual('/with/path/appended/and/some/extra');
    });
    it('Doesnt modify original URI object', function () {
        $uri = new \GuzzleHttp\Psr7\Uri('http://some.uri');
        $modifiedUri = \BarLocator\Client\Uri\Manipulator::appendPath($uri, 'append123');
        expect($modifiedUri)->not->toBe($uri);
    });
    it('Properly adds query parameters to URI', function () {
        $uri = (new \GuzzleHttp\Psr7\Uri('http://some.uri'))
            ->withQuery(http_build_query(['paramA'=>1, 'paramB' => 3]));
        $modifiedUri =\BarLocator\Client\Uri\Manipulator::appendQueryParameters($uri, ['extra1' => 2, 'extra2' => 111]);
        parse_str($modifiedUri->getQuery(), $parsedQueryParams);
        expect($parsedQueryParams)->toContainKeyWithValue('paramA', '1');
        expect($parsedQueryParams)->toContainKeyWithValue('paramB', '3');
        expect($parsedQueryParams)->toContainKeyWithValue('extra1', '2');
        expect($parsedQueryParams)->toContainKeyWithValue('extra2', '111');
    });
});
