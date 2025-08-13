<?php

namespace oihana\exceptions\http ;

use Exception;
use Throwable;

/**
 * Throw an 'Forbidden error' (403).
 * @package oihana\exceptions\http
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
class Error403 extends Exception
{
    /**
     * Creates a new Error403 instance.
     * @param string $message
     * @param int $code
     * @param Throwable|null $notFound
     */
    public function __construct( string $message = 'Forbidden error (403)' , int $code = 403 , Throwable|null $notFound = null )
    {
        parent::__construct( $message , $code , $notFound ) ;
    }
}