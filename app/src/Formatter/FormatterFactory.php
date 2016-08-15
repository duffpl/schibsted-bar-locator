<?php

namespace BarLocator\Formatter;

class FormatterFactory
{
    public $types = [];
    private $defaultFormatter;

    /**
     * FormatterFactory constructor.
     * @param $defaultFormatter
     */
    public function __construct($defaultFormatter)
    {
        $this->defaultFormatter = $defaultFormatter;
    }


    public function registerFormatter($type, $class)
    {
        $this->types[$type] = $class;
    }

    public function getRegisteredFormatters()
    {
        return $this->types;
    }

    /**
     * @param $type
     * @return Formatter
     * @throws \Exception
     */
    public function getFormatterByType($type)
    {
        if (!isset($this->types[$type])) {
            throw new \Exception('Formatter of type "' . $type . '" not found.');
        }
        return new $this->types[$type];
    }

    public function getDefaultFormatter()
    {
        return $this->getFormatterByType($this->defaultFormatter);
    }
}