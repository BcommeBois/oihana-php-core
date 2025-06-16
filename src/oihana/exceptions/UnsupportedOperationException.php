<?php

namespace oihana\exceptions;

use Exception;

/**
 * An exception thrown when an operation is unsupported.
 * @package xyz\exceptions
 */
class UnsupportedOperationException extends Exception
{
    use ExceptionTrait ;
}