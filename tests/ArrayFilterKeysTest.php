<?php

use PHPUnit\Framework\TestCase;

class ArrayFilterKeysTest extends TestCase {

    public function testEmptyArray() {
        $this->assertEquals([], array_filter_keys([], ['a', 'b']));
    }

    public function testEmptyKeys() {
        $this->assertEquals([], array_filter_keys(['a' => 10, 'b' => 20, 'c' => 30], []));
    }

    public function testSingleKey() {
        $this->assertEquals(['b' => 20], array_filter_keys(['a' => 10, 'b' => 20, 'c' => 30], ['b']));
    }

    public function testMultipleKeys() {
        $this->assertEquals(['a' => 10, 'b' => 20], array_filter_keys(['a' => 10, 'b' => 20, 'c' => 30], ['b', 'a']));
    }

    public function testAllKeysMissing() {
        $this->assertEquals([], array_filter_keys(['a' => 10, 'b' => 20, 'c' => 30], ['d', 'e']));
    }

    public function testOneKeyMissing() {
        $this->assertEquals(['c' => 30], array_filter_keys(['a' => 10, 'b' => 20, 'c' => 30], ['c', 'd']));
    }

    public function testDuplicateKeys() {
        $this->assertEquals(['a' => 10, 'b' => 20], array_filter_keys(['a' => 10, 'b' => 20, 'c' => 30], ['b', 'a', 'b']));
    }

    public function testDuplicateValues() {
        $this->assertEquals(['b' => 20, 'c' => 10], array_filter_keys(['a' => 10, 'b' => 20, 'c' => 10], ['b', 'c']));
    }

}
