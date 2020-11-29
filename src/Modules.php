<?php

namespace Chizu\DI;

use Ds\Map;

class Modules
{
    protected Map $modules;

    public function add(string $name, $class): void
    {
        $this->modules->put($name, $class);
    }

    public function has(string $name): bool
    {
        return $this->modules->hasKey($name);
    }

    public function get(string $name): object
    {
        $class = $this->modules->get($name);

        if (is_string($class))
        {
            return new $class();
        }

        return $class;
    }

    public function __construct(array $modules = [])
    {
        $this->modules = new Map($modules);
    }
}