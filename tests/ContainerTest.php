<?php

namespace Tests;

use Chizu\DI\Container;
use Chizu\DI\Dependency;
use Chizu\DI\MethodCall;
use PHPUnit\Framework\TestCase;
use Tests\TestDependencies\TestDependency;
use Tests\TestDependencies\UseTestDependency;

class ContainerTest extends TestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();

        $dep = new Dependency(TestDependency::class);

        $dep->addCall(new MethodCall('setText', ['text']));

        $this->container->add(TestDependency::class, $dep);

        $this->container->add(UseTestDependency::class, new Dependency(UseTestDependency::class, ['text' => 'text']));
    }

    public function testCreateByKey(): void
    {
        /**
         * @var UseTestDependency $instance
         */
        $instance = $this->container->createByKey(UseTestDependency::class);

        self::assertInstanceOf(UseTestDependency::class, $instance);
        self::assertEquals('text', $instance->getDep()->getText());
        self::assertEquals('text', $instance->getText());
    }
}