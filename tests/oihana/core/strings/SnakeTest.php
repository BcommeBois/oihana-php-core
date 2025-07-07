<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class SnakeTest extends TestCase
{
    public function testSnakeWithValidStrings()
    {
        $this->assertEquals('hello_world' , snake('hello_world' ));
        $this->assertEquals('hello_world' , snake('helloWorld'  ));
        $this->assertEquals('hello_world' , snake('HelloWorld'  ));
        $this->assertEquals('hello_world' , snake('hello world' ));
        $this->assertEquals('hello_world' , snake('helloWorld'  ));
        $this->assertEquals('hello_world' , snake('HelloWorld'  ));
        $this->assertEquals('helloworld'  , snake('helloworld'  ));

        $this->assertEquals('hello_world_café', snake('helloWorldCafé'));

        $this->assertEquals('hello_world123', snake('helloWorld123'));

        $this->assertEquals('hello_world_how_are_you', snake('hello world how are you'));

        $this->assertEquals('hello-world', snake('helloWorld', '-'));
        $this->assertEquals('hello|world', snake('helloWorld', '|'));
        $this->assertEquals('hello world', snake('helloWorld', ' '));
    }

    public function testSnakeWithSpecialCases()
    {
        $this->assertEquals('', snake(''));
        $this->assertEquals('', snake(null));
    }

    public function testSnakeCache()
    {
        // Check that the cache is working by calling the same function several times.
        $firstResult  = snake('helloWorld');
        $secondResult = snake('helloWorld');
        $this->assertEquals($firstResult, $secondResult);

        // Check that different inputs produce different results.
        $this->assertNotEquals(snake('helloWorld'), snake('anotherString'));

        // Check that the cache respects the delimiter.
        $this->assertEquals('hello-world', snake('helloWorld', '-'));
        $this->assertEquals('hello_world', snake('helloWorld')); // délimiteur par défaut
    }
}