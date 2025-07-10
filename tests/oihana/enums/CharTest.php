<?php

namespace oihana\enums ;

use PHPUnit\Framework\TestCase;

class CharTest extends TestCase
{
    public function testConstants()
    {
        $expectedConstants =
        [
            'AMPERSAND' => '&',
            'APOSTROPHE' => "'",
            'AT_SIGN' => '@',
            'ASTERISK' => '*',
            'BACK_SLASH' => '\\',
            'CIRCUMFLEX' => '^',
            'COLON' => ':',
            'COMMA' => ',',
            'DASH' => '-',
            'DOLLAR' => '$',
            'DOT' => '.',
            'DOUBLE_COLON' => '::',
            'DOUBLE_DOT' => '..',
            'DOUBLE_EQUAL' => '==',
            'DOUBLE_HYPHEN' => '--',
            'DOUBLE_PIPE' => '||',
            'DOUBLE_QUOTE' => '"',
            'DOUBLE_SLASH' => '//',
            'EMPTY' => '',
            'EOL' => PHP_EOL,
            'EQUAL' => '=',
            'EXCLAMATION_MARK' => '!',
            'GRAVE_ACCENT' => '`',
            'HASH' => '#',
            'HYPHEN' => '-',
            'LEFT_BRACE' => '{',
            'LEFT_BRACKET' => '[',
            'LEFT_PARENTHESIS' => '(',
            'MODULUS' => '%',
            'NUMBER' => '#',
            'PERCENT' => '%',
            'PIPE' => '|',
            'PLUS' => '+',
            'QUESTION_MARK' => '?',
            'QUOTATION_MARK' => '"',
            'RIGHT_BRACE' => '}',
            'RIGHT_BRACKET' => ']',
            'RIGHT_PARENTHESIS' => ')',
            'SEMI_COLON' => ';',
            'SIMPLE_QUOTE' => "'",
            'SLASH' => '/',
            'SPACE' => ' ',
            'TILDE' => '~',
            'TRIPLE_DOT' => '...',
            'UNDERLINE' => '_',
        ];

        foreach ( $expectedConstants as $constantName => $expectedValue )
        {
            $actualValue = constant(Char::class . '::' . $constantName );
            $this->assertEquals
            (
                $expectedValue,
                $actualValue ,
                "La valeur de la constante $constantName ne correspond pas à la valeur attendue."
            );
        }
    }
}
