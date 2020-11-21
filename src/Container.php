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

    protected Modules $modules;

    /**
     * @return Modules
     */
    public function getModules(): Modules
    {
        return $this->modules;
    }

    protected Singletons $singletons;

    /**
     * @return Singletons
     */
    public function getSingletons(): Singletons
    {
        return $this->singletons;
    }

    public function __construct(array $dependencies = [], array $singletons = [], array $modules = [])
    {
        $this->dependencies = new Dependencies($dependencies);
        $this->singletons = new Singletons($singletons);
        $this->modules = new Modules($modules);
    }
}