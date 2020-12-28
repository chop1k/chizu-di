<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace Chizu\DI;

use Chizu\DI\Exception\DIException;
use Ds\Map;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class Container provides dependency injection functionality.
 *
 * @package Chizu\DI
 */
class Container
{
    /**
     * Map with dependencies.
     *
     * @var Map $dependencies
     */
    protected Map $dependencies;

    /**
     * Returns true if container contains a class.
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
     * Returns dependency.
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
     * Adds dependency to the container.
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
     * Creates dependency instance by dependencies in the container.
     *
     * @param Dependency $dependency
     * Dependency which will be created.
     *
     * @return object
     * Returns dependency instance.
     *
     * @throws DIException
     *
     * @throws ReflectionException
     */
    public function create(Dependency $dependency): object
    {
        $instance = $dependency->getInstance();

        if (!is_null($instance))
        {
            return $instance;
        }

        return $this->createBy(
            $dependency->getClass(),
            $dependency->getArguments(),
            $dependency->getCalls()
        );
    }

    /**
     * Private function which takes all arguments needed for instance creation.
     *
     * @param string $class
     * Class instance of which will be created.
     *
     * @param array $arguments
     * Array of local arguments.
     *
     * @param array $calls
     * Array of methods which will be executed after creation.
     *
     * @return object
     *
     * @throws DIException
     *
     * @throws ReflectionException
     */
    private function createBy(string $class, array $arguments, array $calls): object
    {
        $reflectionClass = new ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            $instance = $reflectionClass->newInstanceArgs([]);
        }
        else
        {
            $instance = $reflectionClass->newInstanceArgs(
                $this->iterateParams(
                    $arguments,
                    $constructor->getParameters()
                )
            );
        }

        $this->executeMethods($instance, $calls);

        return $instance;
    }

    /**
     * Creates dependency instance by dependencies in the container.
     *
     * @param string $name
     * Dependency name.
     *
     * @return object
     * Dependency instance.
     *
     * @throws ReflectionException
     *
     * @throws DIException
     */
    public function createByKey(string $name): object
    {
        return $this->create($this->get($name));
    }

    /**
     * Shortcut for creating object instance by dependencies in the container.
     *
     * @param string $class
     *
     * @return object
     *
     * @throws DIException
     *
     * @throws ReflectionException
     */
    public function createByClass(string $class): object
    {
        return $this->createBy($class, [], []);
    }

    /**
     * Shortcut for executing the method of the given dependency instance.
     *
     * @param Dependency $dependency
     *
     * @param string $method
     * Method to execute.
     *
     * @return mixed
     *
     * @throws DIException
     *
     * @throws ReflectionException
     */
    public function executeDependencyMethod(Dependency $dependency, string $method)
    {
        return $this->executeMethodBy($this->create($dependency), $dependency->getArguments(), $method);
    }

    /**
     * Shortcut for executing method after creating new instance of given class.
     *
     * @param string $class
     * Class to create.
     *
     * @param string $method
     * Method to execute.
     *
     * @return mixed
     *
     * @throws DIException
     *
     * @throws ReflectionException
     */
    public function executeClassMethod(string $class, string $method)
    {
        return $this->executeMethod($this->createByClass($class), $method);
    }

    /**
     * Executes method using dependency injection.
     *
     * @param object $instance
     * 
     * @param string $method
     *
     * @return mixed
     *
     * @throws DIException
     * 
     * @throws ReflectionException
     */
    public function executeMethod(object $instance, string $method)
    {
        return $this->executeMethodBy($instance, [], $method);
    }

    /**
     * Private function which takes all arguments needed for method execution.
     * 
     * @param object $instance
     * Class instance.
     * 
     * @param array $arguments
     * Dependency arguments.
     * 
     * @param string $method
     * Method to execute.
     * 
     * @return mixed
     * 
     * @throws DIException
     * 
     * @throws ReflectionException
     */
    private function executeMethodBy(object $instance, array $arguments, string $method)
    {
        if ($method === '__constructor')
        {
            throw new DIException(
                'It is forbidden to use a constructor as a method for execution. Use create methods instead.'
            );
        }

        $reflectionMethod = new ReflectionMethod($instance, $method);

        if (!$reflectionMethod->isPublic())
        {
            $reflectionMethod->setAccessible(true);
        }

        $result = $reflectionMethod->invokeArgs(
            $instance,
            $this->iterateParams(
                $arguments,
                $reflectionMethod->getParameters()
            )
        );

        if ($reflectionMethod->isPublic())
        {
            $reflectionMethod->setAccessible(true);
        }

        return $result;
    }

    /**
     * Iterate given parameters.
     *
     * @param array $args
     * Dependency arguments.
     *
     * @param array $params
     * Dependency constructor parameters.
     *
     * @return array
     * Returns array of constructor arguments.
     *
     * @throws DIException
     *
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
     * Handles given parameter.
     *
     * @param array $params
     * Dependency arguments.
     *
     * @param ReflectionParameter $parameter
     * Parameter.
     *
     * @return mixed
     * Returns value which will be passed to dependency constructor.
     *
     * @throws DIException
     * Throws if no value found.
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
                return $this->createByKey($fullClass);
            }
        }
        elseif (isset($params[$name]))
        {
            return $params[$name];
        }

        throw new DIException(sprintf('Cannot find value for argument %s', $name));
    }

    /**
     * Executes dependency method.
     *
     * @param object $instance
     * Dependency instance.
     *
     * @param array $calls
     * Dependency calls.
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