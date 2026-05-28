<?php

declare(strict_types=1);

namespace AndrewDyer\Mailer\Tests;

use AndrewDyer\Mailer\Example;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testSayHello(): void
    {
        $pkg = new Example();
        $this->assertSame('Hello, John!', $pkg->sayHello('John'));
    }
}
