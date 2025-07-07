<?php

namespace oihana\core\strings\helpers ;

use PHPUnit\Framework\TestCase;

class SnakeCacheTest extends TestCase
{
    public function setUp(): void
    {
        SnakeCache::flush();
    }

    public function testGetAndHasWithCachedValue()
    {
        SnakeCache::set('helloWorld', '_', 'hello_world');
        $this->assertEquals('hello_world', SnakeCache::get('helloWorld', '_'));
        $this->assertTrue(SnakeCache::has('helloWorld', '_'));
    }

    public function testGetAndHasWithNonCachedValue()
    {
        $this->assertNull(SnakeCache::get('nonexistentKey', '_'));
        $this->assertFalse(SnakeCache::has('nonexistentKey', '_'));
    }

    public function testSetAndGetWithDifferentDelimiters()
    {
        // Stocker des valeurs avec différents délimiteurs
        SnakeCache::set('helloWorld', '_', 'hello_world');
        SnakeCache::set('helloWorld', '-', 'hello-world');
        SnakeCache::set('helloWorld', ' ', 'hello world');

        // Vérifier que chaque valeur peut être récupérée avec son délimiteur correspondant
        $this->assertEquals('hello_world', SnakeCache::get('helloWorld', '_'));
        $this->assertEquals('hello-world', SnakeCache::get('helloWorld', '-'));
        $this->assertEquals('hello world', SnakeCache::get('helloWorld', ' '));

        // Vérifier que has retourne true pour chaque clé et délimiteur
        $this->assertTrue(SnakeCache::has('helloWorld', '_'));
        $this->assertTrue(SnakeCache::has('helloWorld', '-'));
        $this->assertTrue(SnakeCache::has('helloWorld', ' '));
    }

    public function testFlush()
    {
        // Stocker une valeur dans le cache
        SnakeCache::set('helloWorld', '_', 'hello_world');
        $this->assertTrue(SnakeCache::has('helloWorld', '_'));

        // Vider le cache
        SnakeCache::flush();

        // Vérifier que le cache est vide
        $this->assertFalse(SnakeCache::has('helloWorld', '_'));
        $this->assertNull(SnakeCache::get('helloWorld', '_'));
    }

    public function testSetOverwritesExistingValue()
    {
        // Stocker une première valeur
        SnakeCache::set('helloWorld', '_', 'hello_world');
        $this->assertEquals('hello_world', SnakeCache::get('helloWorld', '_'));

        // Stocker une nouvelle valeur avec la même clé et délimiteur
        SnakeCache::set('helloWorld', '_', 'new_hello_world');
        $this->assertEquals('new_hello_world', SnakeCache::get('helloWorld', '_'));
    }

    public function testGetWithDifferentKeysAndDelimiters()
    {
        // Stocker plusieurs valeurs avec différentes clés et délimiteurs
        SnakeCache::set('key1', '_', 'value1');
        SnakeCache::set('key2', '-', 'value2');
        SnakeCache::set('key3', ' ', 'value3');

        // Vérifier que chaque valeur peut être récupérée correctement
        $this->assertEquals('value1', SnakeCache::get('key1', '_'));
        $this->assertEquals('value2', SnakeCache::get('key2', '-'));
        $this->assertEquals('value3', SnakeCache::get('key3', ' '));

        // Vérifier que les valeurs ne sont pas mélangées
        $this->assertNull(SnakeCache::get('key1', '-')); // Mauvais délimiteur
        $this->assertNull(SnakeCache::get('key2', '_')); // Mauvais délimiteur
        $this->assertNull(SnakeCache::get('key3', '_')); // Mauvais délimiteur
    }
}