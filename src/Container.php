<?php

namespace Chizu\DI;

use Chizu\DI\Exception\DIException;
use Ds\Map;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

/**
 * Class Container provides dependency injection functionality
 *
 * @package Chizu\DI
 */
class Container
{
    /**
     * Map with dependencies
     *
     * @var Map $dependencies
     */
    protected Map $dependencies;

    /**
     * Returns true if container contains a class
     *
     * @param string $class
     *
     * @return bool
     */
    public function has(string $class): bool
    {
        return $this->dependencies->hasKey($class);
    }

    /**
     * Returns dependency
     *
     * @param string $class
     *
     * @return Dependency
     */
    public function get(string $class): Dependency
    {
        return $this->dependencies->get($class);
    }

    /**
     * Adds dependency to the container
     *
     * @param string $class
     *
     * @param Dependency $dependency
     */
    public function add(string $class, Dependency $dependency): void
    {
        $this->dependencies->put($class, $dependency);
    }

    /**
     * Container constructor.
     *
     * @param array $values
     */
    public function __construct($values = [])
    {
        $this->dependencies = new Map($values);
    }

    /**
     * Creates dependency instance by dependencies in the container
     *
     * @param string $name
     * Dependency name
     *
     * @return object
     * Dependency instance
     *
     * @throws ReflectionException
     * @throws DIException
     */
    public function create(string $name): object
    {
        $dependency = $this->get($name);

        $instance = $dependency->getInstance();

        if (!is_null($instance))
        {
            return $instance;
        }

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

    /**
     * Iterate given parameters
     *
     * @param array $args
     * Dependency arguments
     *
     * @param array $params
     * Dependency constructor parameters
     *
     * @return array
     * Returns array of constructor arguments
     *
     * @throws DIException
     * @throws ReflectionException
     */
    private function iterateParams(array $args, array $params): array
    {
        $arguments = [];

        foreach ($params as $param)
        {
            $arguments[] = $this->handleParam($args, $param);
        }

        return $arguments;
    }

    /**
     * Handles given parameter
     *
     * @param array $params
     * Dependency arguments
     *
     * @param ReflectionParameter $parameter
     * Parameter
     *
     * @return mixed
     * Returns value which will be passed to dependency constructor
     *
     * @throws DIException
     * Throws if no value found
     *
     * @throws ReflectionException
     */
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

    /**
     * Executes dependency method
     *
     * @param object $instance
     * Dependency instance
     *
     * @param array $calls
     * Dependency calls
     */
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