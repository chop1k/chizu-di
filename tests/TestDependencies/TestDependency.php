<?php

namespace Chizu\DI\Tests\TestDependencies;

use Chizu\DI\Tests\ContainerTest;

/**
 * Class TestDependency is a simple dependency which returns test word.
 *
 * @package Tests\TestDependencies
 */
class TestDependency
{
    /**
     * Returns test word.
     *
     * @return string
     */
    public function test(): string
    {
        return ContainerTest::testWord;
    }
}