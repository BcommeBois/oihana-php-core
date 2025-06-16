<?php

namespace oihana\core\date ;

use PHPUnit\Framework\TestCase;

class IsDateTest extends TestCase
{
    public function testValidDates(): void
    {
        $this->assertTrue(isDate('2020-01-01'));
        $this->assertTrue(isDate('2020-12-31'));
        $this->assertTrue(isDate('2000-02-29')); // Année bissextile
    }

    public function testInvalidDates(): void
    {
        $this->assertFalse(isDate('2020-02-30')); // Février n'a pas 30 jours
        $this->assertFalse(isDate('2020/01/01')); // Format incorrect (utilise des barres obliques au lieu de traits d'union)
        $this->assertFalse(isDate('not a date'));
        $this->assertFalse(isDate('2020-01-01 12:00:00')); // Format par défaut est 'Y-m-d', donc les heures ne sont pas autorisées
    }

    public function testEmptyAndNullDates(): void
    {
        $this->assertFalse(isDate(null));
        $this->assertFalse(isDate(''));
    }

    public function testCustomFormats(): void
    {
        // Format 'd/m/Y'
        $this->assertTrue(isDate('01/01/2020', 'd/m/Y'));
        $this->assertFalse(isDate('01/01/2020')); // Format par défaut est 'Y-m-d'
        $this->assertFalse(isDate('01-01-2020', 'd/m/Y')); // Format incorrect pour le format donné

        // Format 'Y-m-d H:i:s'
        $this->assertTrue(isDate('2020-01-01 12:00:00', 'Y-m-d H:i:s'));
        $this->assertFalse(isDate('2020-01-01', 'Y-m-d H:i:s')); // Manque la partie heure
    }

    public function testEdgeCases(): void
    {
        // Dates limites
        $this->assertTrue(isDate('0000-01-01'));
        $this->assertTrue(isDate('9999-12-31'));

        // Années bissextiles
        $this->assertTrue(isDate('2000-02-29')); // Année bissextile
        $this->assertFalse(isDate('2001-02-29')); // Année non bissextile

        // Dates invalides dans d'autres champs
        $this->assertFalse(isDate('2020-00-01')); // Mois invalide
        $this->assertFalse(isDate('2020-01-00')); // Jour invalide
        $this->assertFalse(isDate('2020-13-01')); // Mois invalide
        $this->assertFalse(isDate('2020-01-32')); // Jour invalide
    }

    public function testSpecialCharacters(): void
    {
        $this->assertFalse(isDate('2020-01-01!'));
        $this->assertFalse(isDate('2020-01-01 12:00')); // Format incorrect si le format par défaut est utilisé
    }

    public function testDifferentLocales(): void
    {
        // Cela dépend du comportement de DateTime avec les locales, mais généralement, les formats sont stricts
        $this->assertFalse(isDate('01/01/2020')); // Format 'Y-m-d' par défaut, donc c'est invalide
    }

    public function testWithCustomSeparators(): void
    {
        // Tester avec des formats qui utilisent des séparateurs différents
        $this->assertTrue(isDate('2020.01.01', 'Y.m.d'));
        $this->assertFalse(isDate('2020-01-01', 'Y.m.d')); // Format incorrect
    }

    public function testWithTimezones(): void
    {
        $this->assertTrue(isDate('2020-01-01 00:00:00', 'Y-m-d H:i:s'));
    }
}