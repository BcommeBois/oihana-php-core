<?php

namespace oihana\interfaces;

/**
 * A contract for objects holding a derived state that can become stale.
 *
 * It lets a producer of change tell its dependents to forget what they
 * derived — without knowing what, or how, they cache it.
 *
 * @package oihana\interfaces
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.1.0
 */
interface Invalidable
{
    /**
     * Drops the cached state, so the next read rebuilds it.
     */
    public function invalidate() : void ;
}