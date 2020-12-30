<?php

namespace Chizu\DI\Tests\TestDependencies;

/**
 * Class CreateTestDependency needs for the create tests.
 *
 * @package Tests\TestDependencies
 */
class CreateTestDependency
{
    /**
     * Test dependency.
     *
     * @var TestDependency $dependency
     */
    protected TestDependency $dependency;

    /**
     * Text which will be injected from local dependency argument.
     *
     * @var string $text
     */
    protected string $text;

    /**
     * CreateTestDependency constructor.
     *
     * @param TestDependency $dependency
     * @param string $text
     */
    public function __construct(TestDependency $dependency, string $text)
    {
        $this->dependency = $dependency;
        $this->text = $text;
    }

    public function test(): string
    {
        return sprintf('%s %s', $this->dependency->test(), $this->text);
    }
}