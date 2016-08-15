<?php

use Symfony\Component\HttpFoundation\Request;

require_once 'bootstrap.php';

Request::setTrustedProxies(array('127.0.0.1'));

$app = new Silex\Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new \BarLocator\Client\GooglePlacesClientServiceProvider(), [
    'config.apikey' => getenv('APIKEY'),
    'config.baseuri' => 'https://maps.googleapis.com/maps/api/place',
    'config.default_location' => new \BarLocator\Geo\Coordinates(54.348545, 18.6532295)
]);
$app->register(new \BarLocator\Formatter\FormatterFactoryServiceProvider(), [
    'formatters' => [
        'xml' => BarLocator\Formatter\XML\Formatter::class,
        'json' => \BarLocator\Formatter\Json\Formatter::class
    ]
]);
$app['controller.place'] = function () use ($app) {
    return new \BarLocator\Controller\Place($app['client'], $app['formatterFactory']);
};
$app->mount('place', function ($place) {
    $place->get('/', 'controller.place:radarAction');
    $place->get("/{placeId}", 'controller.place:detailAction');
});
$app->after(function (\Symfony\Component\HttpFoundation\Request $req, \Symfony\Component\HttpFoundation\Response $response) {
    if ($response->getStatusCode() == 200) {
        $response->setTtl(3600);
    }

});
$app->error(function (\Symfony\Component\HttpKernel\Exception\HttpException $exception) use ($app) {
    $response = new \Symfony\Component\HttpFoundation\JsonResponse(['message' => $exception->getMessage()], $exception->getStatusCode());
    $response->setTtl(0);
    return $response;
});
$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => sys_get_temp_dir() . '/cache',
    'http_cache.esi'       => null
));
//$app['debug'] = true;
//$app->run();
$app['http_cache']->run();