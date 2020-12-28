<?php

namespace Tests;

use Chizu\DI\Container;
use Chizu\DI\Dependency;
use Chizu\DI\Exception\DIException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Tests\TestDependencies\CreateTestDependency;
use Tests\TestDependencies\ExecuteDependencyMethodTestDependency;
use Tests\TestDependencies\ExecuteMethodTestDependency;
use Tests\TestDependencies\TestDependency;

/**
 * Class ContainerTest tests container class methods.
 *
 * @package Tests
 */
class ContainerTest extends TestCase
{
    /**
     * Test word which will be compared with a expected value.
     */
    public const testWord = 'test';

    /**
     * Text for testing a dependency local arguments.
     */
    public const text = 'text';

    /**
     * Container instance.
     *
     * @var Container $container
     */
    protected Container $container;

    /**
     * Shortcut of dependency for the create test.
     *
     * @var Dependency $createDependency
     */
    protected Dependency $createDependency;

    /**
     * Defines dependencies in the container.
     */
    protected function setUp(): void
    {
        $this->container = new Container();

        $this->container->add(TestDependency::class, new Dependency(TestDependency::class));
        $this->container->add(ExecuteMethodTestDependency::class, new Dependency(ExecuteMethodTestDependency::class));

        $this->createDependency = new Dependency(CreateTestDependency::class, ['text' => self::text]);

        $this->container->add(CreateTestDependency::class, $this->createDependency);
    }

    /**
     * Shortcut for returning a test string for comparing.
     *
     * @return string
     */
    protected function getTestString(): string
    {
        return sprintf('%s %s', self::testWord, self::text);
    }

    /**
     * Tests create method of the container.
     *
     * @throws DIException
     * @throws ReflectionException
     */
    public function testCreate(): void
    {
        /**
         * @var CreateTestDependency $test
         */
        $test = $this->container->create($this->createDependency);

        self::assertEquals($this->getTestString(), $test->test());
    }

    /**
     * Tests createByKey method of the container.
     *
     * @throws DIException
     * @throws ReflectionException
     */
    public function testCreateByKey(): void
    {
        self::assertEquals($this->getTestString(), $this->container->createByKey(CreateTestDependency::class)->test());
    }

    /**
     * Tests createByClass method of the container.
     *
     * @throws DIException
     * @throws ReflectionException
     */
    public function testCreateByClass(): void
    {
        self::assertEquals(self::testWord, $this->container->createByClass(TestDependency::class)->test());
    }

    /**
     * Data provider for executeMethod tests.
     *
     * @return array[]
     */
    public function getMethods(): array
    {
        return [
            ['privateMethod', sprintf('%s private', self::testWord)],
            ['protectedMethod', sprintf('%s protected', self::testWord)],
            ['publicMethod', sprintf('%s public', self::testWord)]
        ];
    }

    /**
     * Tests ExecuteMethod method of the container.
     *
     * @dataProvider getMethods
     *
     * @param string $method
     * @param string $expected
     * @throws DIException
     * @throws ReflectionException
     */
    public function testExecuteMethod(string $method, string $expected): void
    {
        $test = new ExecuteMethodTestDependency();

        $result = $this->container->executeMethod($test, $method);

        self::assertEquals($expected, $result);
    }

    /**
     * Tests ExecuteClassMethod method of the container.
     *
     * @dataProvider getMethods
     *
     * @param string $method
     * @param string $expected
     * @throws DIException
     * @throws ReflectionException
     */
    public function testExecuteClassMethod(string $method, string $expected): void
    {
        self::assertEquals($expected, $this->container->executeClassMethod(ExecuteMethodTestDependency::class, $method));
    }

    /**
     * Tests ExecuteDependencyMethod method of the container.
     *
     * @dataProvider getMethods
     *
     * @param string $method
     * @param string $expected
     *
     * @throws DIException
     * @throws ReflectionException
     */
    public function testExecuteDependencyMethod(string $method, string $expected): void
    {
        self::assertEquals(
            sprintf('%s %s', $expected, self::text),
            $this->container->executeDependencyMethod(
                new Dependency(ExecuteDependencyMethodTestDependency::class, ['text' => self::text]),
                $method
            )
        );
    }
}