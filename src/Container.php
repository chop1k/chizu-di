<?php

namespace Chizu\DI;

use Ds\Map;

class Container
{
    protected Map $dependencies;

    /**
     * @return Map
     */
    public function getDependencies(): Map
    {
        return $this->dependencies;
    }

    public function __construct(array $values = [])
    {
        $this->dependencies = new Map($values);
    }

    public function addDependency(string $name, string $class): void
    {
        $this->dependencies->put($name, $class);
    }

    public function getDependency(string $name): object
    {
        $class = $this->dependencies->get($name);

        return new $class();
    }

    public function addSingleton(string $name, object $instance): void
    {
        $this->dependencies->put($name, $instance);
    }

    public function getSingleton(string $name): object
    {
        return $this->dependencies->get($name);
    }
}