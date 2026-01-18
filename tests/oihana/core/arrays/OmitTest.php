<?php

namespace oihana\core\arrays;

use PHPUnit\Framework\TestCase;

// composer test tests/oihana/core/arrays/OmitTest.php

class OmitTest extends TestCase
{
    public function testOmitSingleKey(): void
    {
        $data = [
            'id'       => 42,
            'name'     => 'Alice',
            'email'    => 'alice@example.com',
        ];

        $result = omit($data, ['email']);

        $this->assertSame(
            [
                'id'   => 42,
                'name' => 'Alice',
            ],
            $result
        );

        // original array must remain unchanged
        $this->assertSame(
            [
                'id'    => 42,
                'name'  => 'Alice',
                'email' => 'alice@example.com',
            ],
            $data
        );
    }

    public function testOmitMultipleKeys(): void
    {
        $data = [
            'id'       => 42,
            'name'     => 'Alice',
            'email'    => 'alice@example.com',
            'password' => 'secret',
        ];

        $result = omit($data, ['email', 'password']);

        $this->assertSame(
            [
                'id'   => 42,
                'name' => 'Alice',
            ],
            $result
        );

        // original array unchanged
        $this->assertSame(
            [
                'id'       => 42,
                'name'     => 'Alice',
                'email'    => 'alice@example.com',
                'password' => 'secret',
            ],
            $data
        );
    }

    public function testOmitKeyThatDoesNotExist(): void
    {
        $data = [
            'x' => 1,
            'y' => 2,
        ];

        $result = omit($data, ['z']); // key 'z' does not exist

        $this->assertSame(
            [
                'x' => 1,
                'y' => 2,
            ],
            $result
        );
    }

    public function testOmitEmptyKeysArrayReturnsSameArray(): void
    {
        $data = [
            'foo' => 'bar',
        ];

        $result = omit($data, []);

        $this->assertSame(
            [
                'foo' => 'bar',
            ],
            $result
        );
    }

    public function testOmitOnEmptyArray(): void
    {
        $result = omit([], ['any']);
        $this->assertSame([], $result);
    }

    public function testOmitPreservesNumericKeys(): void
    {
        $result = omit([
            0 => 'zero',
            1 => 'one',
            2 => 'two',
        ], [1]);

        $this->assertSame(
            [
                0 => 'zero',
                2 => 'two',
            ],
            $result
        );
    }
}