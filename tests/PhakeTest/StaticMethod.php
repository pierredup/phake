<?php

declare(strict_types=1);

class PhakeTest_StaticMethod
{
    public $className = \PhakeTest_ClassWithStaticMethod::class;

    public function askSomething()
    {
        $className = $this->className;
        return $className::ask();
    }
}
