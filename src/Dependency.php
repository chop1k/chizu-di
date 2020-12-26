<?php

namespace Chizu\DI;

class Dependency
{
    protected array $arguments;

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function addArgument(string $name, $value): void
    {
        $this->arguments[$name] = $value;
    }

    protected string $class;

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    protected array $calls;

    /**
     * @return array
     */
    public function getCalls(): array
    {
        return $this->calls;
    }

    /**
     * @param array $calls
     */
    public function setCalls(array $calls): void
    {
        $this->calls = $calls;
    }

    public function addCall(MethodCall $call): void
    {
        $this->calls[] = $call;
    }

    public function __construct(string $class = "", array $arguments = [], array $calls = [])
    {
        $this->class = $class;
        $this->arguments = $arguments;
        $this->calls = $calls;
    }
}