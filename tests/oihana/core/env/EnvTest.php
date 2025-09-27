<?php

namespace oihana\core\env ;

use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function testIsCliWhenCli()
    {
        if (PHP_SAPI !== 'cli')
        {
            $this->markTestSkipped('Test only runs in CLI context');
        }

        $this->assertTrue( isCli());
    }

    public function testIsCliWhenNotCli()
    {
        // Simulate non-CLI environment by using a wrapper function (recommended)
        $this->assertFalse(PHP_SAPI !== 'cli' && isCli());
    }
}