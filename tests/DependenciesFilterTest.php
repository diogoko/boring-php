<?php

use PHPUnit\Framework\TestCase;

class DependenciesFilterTest extends TestCase {

    public function testEmptyGraph() {
        $this->assertEquals([], dependencies_filter([], ['a']));
    }

    public function testEmptyDeps() {
        $this->assertEquals([], dependencies_filter(['a' => ['b', 'c']], []));
    }

    public function testSingleDep() {
        $this->assertEquals(['a' => ['b', 'c']], dependencies_filter(['a' => ['b', 'c'], 'd' => ['c']], ['a']));
    }

    public function testExplicitDuplicate() {
        $this->assertEquals(['a' => ['b', 'c']], dependencies_filter(['a' => ['b', 'c'], 'd' => ['c']], ['a', 'c']));
    }

    public function testTransitive() {
        $this->assertEquals(['a' => ['b'], 'b' => ['c']], dependencies_filter(['a' => ['b'], 'b' => ['c'], 'd' => ['c']], ['a']));
    }

    public function testMultipleDeps() {
        $this->assertEquals(['a' => ['b', 'c'], 'd' => ['c']], dependencies_filter(['a' => ['b', 'c'], 'd' => ['c']], ['a', 'd']));
    }

    public function testIndirect() {
        $this->assertEquals([], dependencies_filter(['a' => ['b', 'c'], 'd' => ['c']], ['c']));
    }

}
