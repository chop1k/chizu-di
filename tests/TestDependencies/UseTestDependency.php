<?php

namespace Tests\TestDependencies;

class UseTestDependency
{
    protected TestDependency $dep;

    /**
     * @return TestDependency
     */
    public function getDep(): TestDependency
    {
        return $this->dep;
    }

    protected string $text;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    public function __construct(TestDependency $dep, string $text)
    {
        $this->dep = $dep;
        $this->text = $text;
    }
}