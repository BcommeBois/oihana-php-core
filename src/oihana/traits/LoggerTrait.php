<?php

namespace oihana\traits;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    /**
     * The loggable flag.
     * @var bool
     */
    public bool $loggable = false ;

    /**
     * The Logger reference.
     * @var LoggerInterface|mixed
     */
    public ?LoggerInterface $logger ;

    /**
     * The logger parameter constant.
     */
    public const string LOGGER = 'logger' ;

    /**
     * System is unusable.
     * @param string  $message
     * @param array $context
     * @return void
     */
    public function emergency( string $message, array $context = [] ):void
    {
        $this->logger?->notice( $message , $context );
    }

    /**
     * Action must be taken immediately.
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     * @param string  $message
     * @param array $context
     * @return void
     */
    public function alert( string $message, array $context = [] ):void
    {
        $this->logger?->alert( $message , $context );
    }

    /**
     * Critical conditions.
     * Example: Application component unavailable, unexpected exception.
     * @param string  $message
     * @param array $context
     * @return void
     */
    public function critical( string $message, array $context = [] ):void
    {
        $this->logger?->critical( $message , $context );
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     * @param string  $message
     * @param array $context
     * @return void
     */
    public function error( string $message, array $context = [] ):void
    {
        $this->logger?->error( $message , $context );
    }

    /**
     * Exceptional occurrences that are not errors.
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     * @param string  $message
     * @param array $context
     * @return void
     */
    public function warning( string $message, array $context = [] ):void
    {
        $this->logger?->warning( $message , $context );
    }

    /**
     * Normal but significant events.
     * @param string $message
     * @param array $context
     * @return void
     */
    public function notice( string $message, array $context = [] ):void
    {
        $this->logger?->notice( $message , $context );
    }

    /**
     * Interesting events.
     * Example: User logs in, SQL logs.
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info( string $message, array $context = [] ):void
    {
        $this->logger?->info( $message , $context );
    }

    /**
     * Detailed debug information.
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug( string $message, array $context = [] ):void
    {
        $this->logger?->debug( $message , $context );
    }

    /**
     * Logs with an arbitrary level.
     * @param mixed   $level
     * @param string  $message
     * @param array $context
     * @return void
     */
    public function log( mixed $level , string $message, array $context = [] ):void
    {
        $this->logger?->log( $level , $message , $context );
    }

    /**
     * Initialize the internal logger reference.
     * @param array $init
     * @param Container|null $container
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @return ?LoggerInterface
     */
    protected function initLogger( array $init = [] , ?Container $container = null ) :?LoggerInterface
    {
        $logger = $init[ self::LOGGER ] ?? null ;

        if( $logger instanceof LoggerInterface )
        {
            return $logger ;
        }

        if( isset( $container ) && $container->has( LoggerInterface::class ) )
        {
            $logger = $container->get( LoggerInterface::class ) ;
            if( $logger instanceof LoggerInterface )
            {
                return $logger ;
            }
        }

        return null ;
    }
}