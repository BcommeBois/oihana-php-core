<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class KebabTest extends TestCase
{
    public function testKebabWithValidStrings()
    {
        $this->assertEquals('hello-world', kebab('hello-world'));
        $this->assertEquals('hello-world', kebab('helloWorld'));
        $this->assertEquals('hello-world', kebab('HelloWorld'));
        $this->assertEquals('hello-world', kebab('hello world'));

        // camelCase
        $this->assertEquals('hello-world', kebab('helloWorld'));

        // PascalCase
        $this->assertEquals('hello-world', kebab('HelloWorld'));

        $this->assertEquals('helloworld', kebab('helloworld'));

        $this->assertEquals('hello-world-café', kebab('helloWorldCafé'));

        $this->assertEquals('hello-world123', kebab('helloWorld123'));

        $this->assertEquals('hello-world-how-are-you', kebab('hello world how are you'));
    }

    public function testKebabWithSpecialCases()
    {
        $this->assertEquals('', kebab(''));
        $this->assertEquals('', kebab(null));
    }

    public function testKebabCache()
    {
        $firstResult = kebab('helloWorld');
        $secondResult = kebab('helloWorld');
        $this->assertEquals($firstResult, $secondResult);

        $this->assertNotEquals(kebab('helloWorld'), kebab('anotherString'));
    }
}