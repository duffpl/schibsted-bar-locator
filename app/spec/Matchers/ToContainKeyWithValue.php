<?php
namespace spec\Matchers;

class ToContainKeyWithValue
{
    public static function match($result, $key, $value)
    {
        return isset($result[$key]) && $result[$key] === $value;
    }

    public static function description()
    {
        return 'to contain key with value';
    }
}