<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class DeepMergeTest extends TestCase
{
    /**
     * Teste la fusion simple avec des clés de chaîne.
     */
    public function testStringKeysMerge()
    {
        $arr1 = ['a' => 1, 'b' => 2];
        $arr2 = ['b' => 3, 'c' => 4];
        $expected = ['a' => 1, 'b' => 3, 'c' => 4];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }

    /**
     * Teste la fusion avec plusieurs tableaux et des clés de chaîne.
     */
    public function testMultipleStringArraysMerge()
    {
        $arr1 = ['a' => 1];
        $arr2 = ['b' => 2];
        $arr3 = ['a' => 3, 'c' => 4];
        $expected = ['a' => 3, 'b' => 2, 'c' => 4];
        $this->assertEquals($expected, deepMerge($arr1, $arr2, $arr3));
    }

    /**
     * Teste la fusion avec des clés numériques (elles doivent être ajoutées).
     */
    public function testNumericKeysMerge()
    {
        $arr1 = [1, 2];
        $arr2 = [3, 4];
        $expected = [1, 2, 3, 4];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }

    /**
     * Teste la fusion de tableaux mixtes (chaîne et numérique) au niveau racine.
     */
    public function testMixedKeysRootMerge()
    {
        $arr1 = ['a' => 1, 0 => 'x'];
        $arr2 = [1 => 'y', 'b' => 2];
        $expected = ['a' => 1, 'x', 'y', 'b' => 2];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }

    /**
     * Teste la fusion profonde de tableaux imbriqués avec des clés de chaîne.
     */
    public function testDeepMergeStringKeys()
    {
        $arr1 = ['config' => ['host' => 'localhost', 'port' => 80]];
        $arr2 = ['config' => ['port' => 443, 'protocol' => 'https']];
        $expected = [
            'config' => [
                'host' => 'localhost',
                'port' => 443,
                'protocol' => 'https'
            ]
        ];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }

    /**
     * Teste la fusion profonde de tableaux imbriqués avec des clés numériques.
     */
    public function testDeepMergeNumericKeys()
    {
        $arr1 = ['data' => [1, 2]];
        $arr2 = ['data' => [3, 4]];
        $expected = ['data' => [1, 2, 3, 4]];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }

    /**
     * Teste la fusion profonde de tableaux imbriqués mixtes (chaîne et numérique).
     */
    public function testDeepMergeMixedKeys()
    {
        $arr1 = [
            'settings' => [
                'general' => ['name' => 'App', 'version' => '1.0'],
                'features' => ['featureA', 'featureB']
            ]
        ];
        $arr2 = [
            'settings' => [
                'general' => ['version' => '1.1', 'debug' => true],
                'features' => ['featureC']
            ]
        ];
        $expected = [
            'settings' => [
                'general' => [
                    'name' => 'App',
                    'version' => '1.1',
                    'debug' => true
                ],
                'features' => ['featureA', 'featureB', 'featureC']
            ]
        ];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }

    /**
     * Teste la fusion avec un tableau vide.
     */
    public function testMergeWithEmptyArray()
    {
        $arr1 = ['a' => 1];
        $arr2 = [];
        $expected = ['a' => 1];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
        $this->assertEquals($expected, deepMerge($arr2, $arr1)); // Teste l'ordre
    }

    /**
     * Teste la fusion de tableaux vides.
     */
    public function testMergeEmptyArrays()
    {
        $arr1 = [];
        $arr2 = [];
        $expected = [];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }

    /**
     * Teste la fusion avec des valeurs nulles.
     */
    public function testMergeWithNullValues()
    {
        $arr1 = ['a' => 1, 'b' => null];
        $arr2 = ['b' => 2, 'c' => null];
        $expected = ['a' => 1, 'b' => 2, 'c' => null];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }

    /**
     * Teste les niveaux de profondeur multiples.
     */
    public function testMultipleDepthLevels()
    {
        $arr1 = [
            'l1' => [
                'l2a' => ['l3x' => 1],
                'l2b' => [1, 2]
            ]
        ];
        $arr2 = [
            'l1' => [
                'l2a' => ['l3y' => 2],
                'l2b' => [3]
            ]
        ];
        $expected = [
            'l1' => [
                'l2a' => [
                    'l3x' => 1,
                    'l3y' => 2
                ],
                'l2b' => [1, 2, 3]
            ]
        ];
        $this->assertEquals($expected, deepMerge($arr1, $arr2));
    }
}
