<?php

namespace Chizu\DI\Tests\TestDependencies;

/**
 * Class ExecuteMethodTestDependency contain methods for executeMethod tests.
 *
 * @package Tests\TestDependencies
 */
class ExecuteMethodTestDependency
{
    private function privateMethod(TestDependency $test): string
    {
        return sprintf('%s private', $test->test());
    }

    protected function protectedMethod(TestDependency $test): string
    {
        return sprintf('%s protected', $test->test());
    }

    public function publicMethod(TestDependency $test): string
    {
        return sprintf('%s public', $test->test());
    }
}