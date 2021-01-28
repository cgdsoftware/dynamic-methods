<?php

namespace LaravelEnso\DynamicMethods\Traits;

use BadMethodCallException;
use Closure;

trait Methods
{
    protected static array $dynamicMethods = [];

    public function __call($method, $args)
    {
        if (isset(static::$dynamicMethods[$method])) {
            $closure = Closure::bind(
                static::$dynamicMethods[$method],
                $this,
                static::class
            );

            return $closure(...$args);
        }

        if (method_exists(parent::class, '__call')) {
            return parent::__call($method, $args);
        }

        throw new BadMethodCallException(
            'Method '.static::class.'::'.$method.'() not found'
        );
    }

    public static function addDynamicMethod($name, Closure $method): void
    {
        static::$dynamicMethods[$name] = $method;
    }

    public static function hasMethod($name): bool
    {
        return isset(static::$dynamicMethods[$name]);
    }
}
