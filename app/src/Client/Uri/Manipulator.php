<?php

namespace BarLocator\Client\Uri;

use GuzzleHttp\Psr7\Uri;

class Manipulator
{
    /**
     * @param Uri $uri
     * @param array $queryParameters
     * @return Uri
     */
    public static function appendQueryParameters(Uri $uri, array $queryParameters)
    {
        $uriParameters = [];
        parse_str($uri->getQuery(), $uriParameters);
        $mergedQueryParameters = array_merge($uriParameters, $queryParameters);
        return $uri->withQuery(http_build_query($mergedQueryParameters));
    }

    /**
     * @param Uri $uri
     * @param string $path
     * @return Uri
     */
    public static function appendPath(Uri $uri, string $path)
    {
        return $uri->withPath(join('/', [$uri->getPath(), $path]));
    }
}