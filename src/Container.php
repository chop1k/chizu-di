<?php

namespace Chizu\DI;

use Chizu\DI\Exception\DIException;
use Ds\Map;
use ReflectionClass;
use ReflectionParameter;

class Container
{
    protected Map $dependencies;

    public function has(string $class): bool
    {
        return $this->dependencies->hasKey($class);
    }

    public function get(string $class): Dependency
    {
        return $this->dependencies->get($class);
    }

    public function add(string $class, Dependency $dependency): void
    {
        $this->dependencies->put($class, $dependency);
    }

    public function __construct($values = [])
    {
        $this->dependencies = new Map($values);
    }

    public function create(string $name): object
    {
        $dependency = $this->get($name);

        $reflectionClass = new ReflectionClass($dependency->getClass());

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            $instance = $reflectionClass->newInstanceArgs([]);
        }
        else
        {
            $instance = $reflectionClass->newInstanceArgs(
                $this->iterateParams(
                    $dependency->getArguments(),
                    $constructor->getParameters()
                )
            );
        }

        $this->executeMethods($instance, $dependency->getCalls());

        return $instance;
    }

    private function iterateParams(array $args, array $params): array
    {
        $arguments = [];

        foreach ($params as $param)
        {
            $arguments[] = $this->handleParam($args, $param);
        }

        return $arguments;
    }

    private function handleParam(array $params, ReflectionParameter $parameter)
    {
        $class = $parameter->getClass();
        $name = $parameter->getName();

        if (!is_null($class))
        {
            $fullClass = $class->getName();

            if (!$this->has($fullClass))
            {
                if (isset($params[$name]))
                {
                    return $params[$name];
                }
            }
            else
            {
                return $this->create($fullClass);
            }
        }
        elseif (isset($params[$name]))
        {
            return $params[$name];
        }

        throw new DIException(sprintf('Cannot find value for argument %s', $name));
    }

    private function executeMethods(object $instance, array $calls): void
    {
        /**
         * @var MethodCall $call
         */
        foreach ($calls as $call)
        {
            $name = $call->getName();

            $instance->$name(...$call->getArguments());
        }
    }
}