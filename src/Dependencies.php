<?php

namespace Chizu\DI;

use Ds\Map;

class Dependencies
{
    protected Map $dependencies;

    public function add(string $name, string $class): void
    {
        $this->dependencies->put($name, $class);
    }

    public function get(string $name): object
    {
        $class = $this->dependencies->get($name);

        return new $class();
    }

    public function __construct(array $dependencies = [])
    {
        $this->dependencies = new Map($dependencies);
    }
}