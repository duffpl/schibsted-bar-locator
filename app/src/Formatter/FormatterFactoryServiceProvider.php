<?php

namespace BarLocator\Formatter;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class FormatterFactoryServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['formatterFactory'] = function () use ($app){
            $factory = new FormatterFactory('json');
            foreach ($app['formatters'] as $type => $class) {
                $factory->registerFormatter($type, $class);
            }
            return $factory;
        };
    }
}
