<?php

namespace Tests\TestDependencies;

/**
 * Class ExecuteDependencyMethodTestDependency contain methods for executeDependencyMethod test.
 *
 * @package Tests\TestDependencies
 */
class ExecuteDependencyMethodTestDependency
{
    /**
     * Text which will be injected from local dependency argument.
     *
     * @var string $text
     */
    protected string $text;

    /**
     * ExecuteDependencyMethodTestDependency constructor.
     *
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    private function privateMethod(TestDependency $test): string
    {
        return sprintf('%s private %s', $test->test(), $this->text);
    }

    protected function protectedMethod(TestDependency $test): string
    {
        return sprintf('%s protected %s', $test->test(), $this->text);
    }

    public function publicMethod(TestDependency $test): string
    {
        return sprintf('%s public %s', $test->test(), $this->text);
    }

}