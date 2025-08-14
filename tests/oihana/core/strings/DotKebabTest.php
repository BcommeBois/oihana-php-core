<?php

namespace oihana\core\strings ;

use oihana\core\strings\helpers\SnakeCache;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DotKebabTest extends TestCase
{
    protected function setUp(): void
    {
        // Clear the snake cache before each test to avoid side effects
        SnakeCache::flush();
    }

    #[DataProvider('provideDotKebabCases')]
    public function testDotKebab(?string $input, string $expected): void
    {
        $this->assertSame($expected, dotKebab($input));
    }

    public static function provideDotKebabCases(): array
    {
        return [
            // Null or empty
            [null, ''],
            ['', ''],

            // Single words
            ['foo', 'foo'],
            ['Foo', 'foo'],

            // camelCase
            ['serverAskJwtSecret', 'server.ask-jwt-secret'],
            ['userProfilePage', 'user.profile-page'],

            // PascalCase
            ['UserProfilePage', 'user.profile-page'],
            ['APIKeyManager', 'api.key-manager'],

            // With spaces
            ['Server Ask Jwt Secret', 'server.ask-jwt-secret'],

            // Mixed cases and acronyms
            ['MyXMLParser', 'my.xml-parser'],
            ['HTMLParser', 'html.parser'],

            // Already lowercase with delimiters
            ['server-ask-jwt-secret', 'server.ask-jwt-secret'],
        ];
    }
}