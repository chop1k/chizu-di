<?php

namespace Chizu\DI;

class Container
{
    protected Dependencies $dependencies;

    /**
     * @return Dependencies
     */
    public function getDependencies(): Dependencies
    {
        return $this->dependencies;
    }

    protected Singletons $singletons;

    /**
     * @return Singletons
     */
    public function getSingletons(): Singletons
    {
        return $this->singletons;
    }

    public function __construct(array $dependencies = [], array $singletons = [])
    {
        $this->dependencies = new Dependencies($dependencies);
        $this->singletons = new Singletons($singletons);
    }
}