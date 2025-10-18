<?php

namespace tests\oihana\core\documents\mocks;

class MockFormatDocument
{
    public function __construct( ?string $name = null , ?string $greeting = null )
    {
        $this->name     = $name ;
        $this->greeting = $greeting ;
    }

    public ?string $name     ;
    public ?string $greeting ;
}