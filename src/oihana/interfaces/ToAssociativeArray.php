<?php

namespace oihana\interfaces;

/**
 * Interface for objects that can be converted to associative arrays.
 *
 * This interface defines a contract for objects that can provide an array representation of their data,
 * useful for serialization, data export, or configuration management.
 *
 * @package oihana\interfaces
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.8
 */
interface ToAssociativeArray
{
    /**
     * Generates an associative array from the public properties of a given class or object.
     * @return array The resulting associative array of properties.
     */
    public function toArray( array $options = [] ): array;
}