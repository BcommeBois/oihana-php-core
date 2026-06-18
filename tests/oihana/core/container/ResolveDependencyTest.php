<?php

declare(strict_types=1);

namespace tests\oihana\core\container;

use Exception;
use stdClass;

use PHPUnit\Framework\TestCase;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function oihana\core\container\resolveDependency;

final class ResolveDependencyTest extends TestCase
{
    /**
     * Builds a minimal in-memory PSR-11 container backed by an associative array.
     *
     * @param array<string,mixed> $entries
     */
    private function container( array $entries = [] ): ContainerInterface
    {
        return new class( $entries ) implements ContainerInterface
        {
            /**
             * @param array<string,mixed> $entries
             */
            public function __construct( private array $entries ) {}

            public function get( string $id ): mixed
            {
                if ( !$this->has( $id ) )
                {
                    throw new class extends Exception implements NotFoundExceptionInterface {};
                }
                return $this->entries[ $id ];
            }

            public function has( string $id ): bool
            {
                return array_key_exists( $id , $this->entries );
            }
        };
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testResolvesAnExistingEntryFromTheContainer(): void
    {
        $service   = new stdClass();
        $container = $this->container([ 'svc' => $service ]);

        $this->assertSame( $service , resolveDependency( 'svc' , $container ) );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testReturnsDefaultWhenTheEntryIsNotInTheContainer(): void
    {
        $default   = new stdClass();
        $container = $this->container();

        $this->assertSame( $default , resolveDependency( 'missing' , $container , $default ) );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testReturnsDefaultWhenContainerIsNull(): void
    {
        $default = new stdClass();

        $this->assertSame( $default , resolveDependency( 'svc' , null , $default ) );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testReturnsDefaultWhenDependencyIsNull(): void
    {
        $container = $this->container([ 'svc' => new stdClass() ]);

        $this->assertNull( resolveDependency( null , $container ) );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testReturnsDefaultWhenDependencyIsEmptyString(): void
    {
        $default   = new stdClass();
        $container = $this->container([ '' => new stdClass() ]);

        $this->assertSame( $default , resolveDependency( '' , $container , $default ) );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testDefaultsToNullWhenNothingResolves(): void
    {
        $container = $this->container();

        $this->assertNull( resolveDependency( 'missing' , $container ) );
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function testPropagatesAContainerException(): void
    {
        $container = $this->createStub( ContainerInterface::class );
        $container->method( 'has' )->willReturn( true );
        $container->method( 'get' )->willThrowException
        (
            new class extends Exception implements ContainerExceptionInterface {}
        );

        $this->expectException( ContainerExceptionInterface::class );

        resolveDependency( 'boom' , $container );
    }
}
