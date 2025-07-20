<?php

namespace oihana\core\arrays;

use PHPUnit\Framework\TestCase;

// composer test tests/oihana/core/arrays/DeleteTest.php

class DeleteTest extends TestCase
{
    public function testDeleteFlatKey()
    {
        $input = ['name' => 'Alice', 'email' => 'alice@example.com'];
        $result = delete($input, 'email');

        $this->assertSame(['name' => 'Alice'], $result);
    }

    public function testDeleteNestedKey()
    {
        $input = ['user' => ['profile' => ['age' => 30, 'gender' => 'f']]];
        $result = delete($input, 'user.profile.age');

        $this->assertSame(['user' => ['profile' => ['gender' => 'f']]], $result);
    }

    public function testDeleteUsingArrayPath()
    {
        $input = ['settings' => ['notifications' => ['email' => true, 'sms' => false]]];
        $result = delete( $input, ['settings', 'notifications', 'email'] );

        $this->assertSame(['settings' => ['notifications' => ['sms' => false]]], $result);
    }

    public function testDeleteAllWithStar()
    {
        $input = ['a' => 1, 'b' => 2];
        $result = delete($input, '*');

        $this->assertSame([], $result);
    }

    public function testDeleteNonExistingKeyReturnsUnchanged()
    {
        $input = ['foo' => 'bar'];
        $result = delete($input, 'baz');

        $this->assertSame(['foo' => 'bar'], $result);
    }

    public function testDeleteWithNonArrayReturnsInput()
    {
        $input = 'not an array';
        $result = delete($input, 'key');

        $this->assertSame('not an array', $result);
    }
}