<?php

use PHPUnit\Framework\TestCase;

class ArrayFlattenTest extends TestCase {

    public function testEmpty() {
        $this->assertEquals([], array_flatten([]));
    }

    public function testSingle() {
        $this->assertEquals([10], array_flatten([[10]]));
    }

    public function testMultiple() {
        $this->assertEquals([10, 20, 30, 40], array_flatten([[10], [20, 30], [], [40]]));
    }

    public function testNested() {
        $this->assertEquals([10, 20, [30, 40]], array_flatten([[10], [20, [30, 40]]]));
    }

}
