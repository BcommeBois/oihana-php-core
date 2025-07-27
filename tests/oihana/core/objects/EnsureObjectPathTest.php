<?php

declare(strict_types=1);

namespace oihana\core\objects;

use PHPUnit\Framework\TestCase;
use stdClass;

final class EnsureObjectPathTest extends TestCase
{
    public function testEnsureNewProperty(): void
    {
        $obj = new stdClass();
        $nested =& ensureObjectPath($obj, 'settings');

        $this->assertInstanceOf(stdClass::class, $nested);
        $nested->theme = 'dark';

        $this->assertEquals('dark', $obj->settings->theme);
    }

    public function testEnsureExistingObject(): void
    {
        $obj = new stdClass();
        $obj->config = (object)['lang' => 'fr'];

        $ref =& ensureObjectPath($obj, 'config');
        $ref->lang = 'en';

        $this->assertSame('en', $obj->config->lang);
    }

    public function testOverwriteNonObject(): void
    {
        $obj = new stdClass();
        $obj->data = 42;

        $ref =& ensureObjectPath($obj, 'data');
        $ref->value = 123;

        $this->assertInstanceOf(stdClass::class, $obj->data);
        $this->assertSame(123, $obj->data->value);
    }
}