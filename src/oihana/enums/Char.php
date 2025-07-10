<?php

namespace oihana\enums;

use oihana\reflections\traits\ConstantsTrait;

/**
 * The enumeration of all basic chars.
 */
class Char
{
    use ConstantsTrait ;

    const string AMPERSAND         = "&" ;
    const string APOSTROPHE        = "'" ;
    const string AT_SIGN           = '@' ;
    const string ASTERISK          = '*' ;
    const string BACK_SLASH        = '\\' ;
    const string CIRCUMFLEX        = '^' ;
    const string COLON             = ':' ;
    const string COMMA             = ',' ;
    const string DASH              = '-' ;
    const string DOLLAR            = '$' ;
    const string DOT               = '.' ;
    const string DOUBLE_COLON      = '::' ;
    const string DOUBLE_DOT        = '..' ;
    const string DOUBLE_EQUAL      = '==' ;
    const string DOUBLE_HYPHEN     = '--' ;
    const string DOUBLE_PIPE       = '||' ;
    const string DOUBLE_QUOTE      = '"' ;
    const string DOUBLE_SLASH      = '//' ;
    const string EMPTY             = '' ;
    const string EOL               = PHP_EOL ;
    const string EQUAL             = '=' ;
    const string EXCLAMATION_MARK  = '!' ;
    const string GRAVE_ACCENT      = '`' ;
    const string HASH              = '#' ;
    const string HYPHEN            = '-' ;
    const string LEFT_BRACE        = '{' ;
    const string LEFT_BRACKET      = '[' ;
    const string LEFT_PARENTHESIS  = '(' ;
    const string MODULUS           = '%' ;
    const string NUMBER            = '#' ;
    const string PERCENT           = '%' ;
    const string PIPE              = '|' ;
    const string PLUS              = '+' ;
    const string QUESTION_MARK     = '?' ;
    const string QUOTATION_MARK    = '"' ;
    const string RIGHT_BRACE       = '}' ;
    const string RIGHT_BRACKET     = ']' ;
    const string RIGHT_PARENTHESIS = ')' ;
    const string SEMI_COLON        = ';' ;
    const string SIMPLE_QUOTE      = "'" ;
    const string SLASH             = '/' ;
    const string SPACE             = ' ' ;
    const string TILDE             = '~' ;
    const string TRIPLE_DOT        = '...' ;
    const string UNDERLINE         = '_' ;
}