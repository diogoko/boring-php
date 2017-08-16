<?php

use PHPUnit\Framework\TestCase;

class ArrayValuesFromKeysTest extends TestCase {

    public function testEmptyArray() {
        $this->assertEquals([], array_values_from_keys([], ['a', 'b']));
    }

    public function testEmptyKeys() {
        $this->assertEquals([], array_values_from_keys(['a' => 10, 'b' => 20, 'c' => 30], []));
    }

    public function testSingleKey() {
        $this->assertEquals([20], array_values_from_keys(['a' => 10, 'b' => 20, 'c' => 30], ['b']));
    }

    public function testMultipleKeys() {
        $this->assertEquals([10, 20], array_values_from_keys(['a' => 10, 'b' => 20, 'c' => 30], ['b', 'a']));
    }

    public function testAllKeysMissing() {
        $this->assertEquals([], array_values_from_keys(['a' => 10, 'b' => 20, 'c' => 30], ['d', 'e']));
    }

    public function testOneKeyMissing() {
        $this->assertEquals([30], array_values_from_keys(['a' => 10, 'b' => 20, 'c' => 30], ['c', 'd']));
    }

    public function testDuplicateKeys() {
        $this->assertEquals([10, 20], array_values_from_keys(['a' => 10, 'b' => 20, 'c' => 30], ['b', 'a', 'b']));
    }

    public function testDuplicateValues() {
        $this->assertEquals([20, 10], array_values_from_keys(['a' => 10, 'b' => 20, 'c' => 10], ['b', 'c']));
    }

}
