<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class RandomKeyTest extends TestCase
{
    public function testWithPrefix() : void
    {
        $this->assertMatchesRegularExpression( '/^user_\d+$/' , randomKey( 'user' ) ) ;
    }

    public function testWithNullPrefix() : void
    {
        $this->assertMatchesRegularExpression( '/^\d+$/' , randomKey( null ) ) ;
    }

    public function testWithCustomSeparator() : void
    {
        $this->assertMatchesRegularExpression( '/^order-\d+$/' , randomKey( 'order' , '-' ) ) ;
    }

    public function testEmptyPrefixStillAddsSeparator() : void
    {
        // is_string('') is true, so the separator is prepended to the random part.
        $this->assertMatchesRegularExpression( '/^_\d+$/' , randomKey( '' ) ) ;
    }
}
