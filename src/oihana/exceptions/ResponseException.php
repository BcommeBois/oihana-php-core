<?php

namespace oihana\exceptions ;

use Exception ;

/**
 * An exception thrown when a validation failed.
 * @package xyz\exceptions
 */
class ResponseException extends Exception
{
    use ExceptionTrait ;
}