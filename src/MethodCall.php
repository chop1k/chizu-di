<?php

namespace Chizu\DI;

/**
 * Class MethodCall represents class which stores data for dependency method call.
 *
 * @package Chizu\DI
 */
class MethodCall
{
    /**
     * Contains method name.
     *
     * @var string $name
     */
    protected string $name;

    /**
     * Returns method name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets method name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Contains method arguments.
     *
     * @var array $arguments
     */
    protected array $arguments;

    /**
     * Returns array of arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Sets array arguments.
     *
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * MethodCall constructor.
     *
     * @param string $name
     * @param array $arguments
     */
    public function __construct(string $name = "", array $arguments = [])
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }
}