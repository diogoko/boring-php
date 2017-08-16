<?php

use PHPUnit\Framework\TestCase;

class TopologicalSortTest extends TestCase {

    public function testEmptyGraph() {
        $this->assertEquals([], topological_sort([]));
    }

    public function testSingle() {
        $this->assertEquals(['b', 'c', 'a'], topological_sort(['a' => ['b', 'c']]));
    }

    public function testDuplicate() {
        $this->assertEquals(['b', 'c', 'a', 'd'], topological_sort(['a' => ['b', 'c'], 'd' => ['c']]));
    }

    public function testTransitive() {
        $this->assertEquals(['c', 'b', 'a'], topological_sort(['a' => ['b'], 'b' => ['c']]));
    }

    public function testDiamond() {
        $this->assertEquals(['d', 'b', 'c', 'a'], topological_sort(['a' => ['b', 'c'], 'b' => ['d'], 'c' => ['d']]));
    }

}
