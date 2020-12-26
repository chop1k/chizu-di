<?php

namespace Chizu\DI;

/**
 * Class Dependency represents structure which stores data needed for dependency injection
 *
 * @package Chizu\DI
 */
class Dependency
{
    /**
     * Contains array of arguments needed for dependency constructor
     *
     * @var array $arguments
     */
    protected array $arguments;

    /**
     * Returns array of arguments needed for dependency constructor
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Sets array of arguments needed for dependency constructor
     *
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * Adds argument for dependency constructor
     *
     * @param string $name
     * Argument name
     *
     * @param $value
     * Argument value
     */
    public function addArgument(string $name, $value): void
    {
        $this->arguments[$name] = $value;
    }

    /**
     * Contains class of dependency
     *
     * @var string $class
     */
    protected string $class;

    /**
     * Returns class of dependency
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Sets class of dependency
     *
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    /**
     * Contains array of method calls
     *
     * @var array $calls
     */
    protected array $calls;

    /**
     * Returns array of method calls
     *
     * @return array
     */
    public function getCalls(): array
    {
        return $this->calls;
    }

    /**
     * Sets array of method calls
     *
     * @param array $calls
     */
    public function setCalls(array $calls): void
    {
        $this->calls = $calls;
    }

    /**
     * Adds method call
     *
     * @param MethodCall $call
     */
    public function addCall(MethodCall $call): void
    {
        $this->calls[] = $call;
    }

    /**
     * Contains dependency instance if dependency is singleton, null otherwise
     *
     * @var object|null $instance
     */
    protected ?object $instance;

    /**
     * Returns dependency instance if dependency is singleton, null otherwise
     *
     * @return object|null
     */
    public function getInstance(): ?object
    {
        return $this->instance;
    }

    /**
     * Sets dependency instance if dependency is singleton
     *
     * @param object|null $instance
     */
    public function setInstance(?object $instance): void
    {
        $this->instance = $instance;
    }

    /**
     * Dependency constructor.
     *
     * @param string|object|null $class
     * @param array $arguments
     * @param array $calls
     */
    public function __construct(string $class, array $arguments = [], array $calls = [])
    {
        $this->class = is_string($class) ? $class : false;
        $this->arguments = $arguments;
        $this->calls = $calls;
        $this->instance = is_object($class) ? $class : null;
    }
}