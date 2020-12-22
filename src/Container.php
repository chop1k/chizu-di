<?php

namespace Chizu\DI;

use Ds\Map;

class Container
{
    protected Map $dependencies;

    public function has(string $class): bool
    {
        return $this->dependencies->hasKey($class);
    }

    public function get(string $class)
    {
        return $this->dependencies->get($class);
    }

    public function add(string $class, $dependency): void
    {
        $this->dependencies->put($class, $dependency);
    }

    public function __construct($values = [])
    {
        $this->dependencies = new Map($values);
    }
}