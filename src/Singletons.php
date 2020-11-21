<?php

namespace Chizu\DI;

use Ds\Map;

class Singletons
{
    protected Map $singletons;

    public function add(string $name, string $class): void
    {
        $this->singletons->put($name, $class);
    }

    public function get(string $name): object
    {
        $class = $this->singletons->get($name);

        if (is_string($class))
        {
            return new $class();
        }

        return $class;
    }

    public function __construct(array $singletons = [])
    {
        $this->singletons = new Map($singletons);
    }
}