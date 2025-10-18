<?php

namespace tests\oihana\core\arrays\mocks;

use ArrayAccess;

class MockArrayAccess implements ArrayAccess
{
    public function __construct( array $data = [])
    {
        $this->data = $data;
    }

    private array $data = [];

    public function offsetExists( $offset ): bool
    {
        return isset( $this->data[$offset] );
    }

    public function offsetGet( $offset ) : mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet( $offset , $value ): void
    {
        if (is_null( $offset ) )
        {
            $this->data[] = $value;
        }
        else
        {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset( $offset ): void
    {
        unset( $this->data[ $offset ] );
    }
}