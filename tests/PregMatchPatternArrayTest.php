<?php

use PHPUnit\Framework\TestCase;

class PregMatchPatternArrayTest extends TestCase {
    
    public function testNoPatterns() {
        $this->assertNull(preg_match_pattern_array([], 'abcdef'));
    }
    
    public function testOnePatternMatching() {
        $this->assertEquals('/abc/', preg_match_pattern_array(['/abc/'], 'abcdef'));
    }
    
    public function testOnePatternNotMatching() {
        $this->assertNull(preg_match_pattern_array(['/xyz/'], 'abcdef'));
    }
    
    public function testManyPatternsMatching() {
        $this->assertEquals('/abc/', preg_match_pattern_array(['/\d+/', '/abc/', '/xyz/'], 'abcdef'));
    }
    
    public function testManyPatternsNotMatching() {
        $this->assertNull(preg_match_pattern_array(['/\d+/', '/abc/', '/xyz/'], 'z'));
    }
    
    public function testMatchesParameter() {
        $this->assertEquals('/abc(\w+)/', preg_match_pattern_array(['/\d+/', '/abc(\w+)/', '/xyz/'], 'abcdef', $matches));
        $this->assertEquals(['abcdef', 'def'], $matches);
    }
    
    public function testOffsetParameter() {
        $this->assertEquals('/def/', preg_match_pattern_array(['/\d+/', '/def/', '/xyz/'], 'abcdef', $matches, 0, 3));
    }
    
    public function testMatchValueFlag() {
        $this->assertEquals('/abc/', preg_match_pattern_array(['/\d+/', '/abc/', '/xyz/'], 'abcdef', $matches, PREG_MATCH_VALUE));
    }
    
    public function testMatchKeyFlag() {
        $this->assertEquals(2, preg_match_pattern_array(['/\d+/' => 1, '/abc/' => 2, '/xyz/' => 3], 'abcdef', $matches, PREG_MATCH_KEY));
    }
    
    public function testReturnValueFlag() {
        $this->assertEquals('/abc/', preg_match_pattern_array(['/\d+/', '/abc/', '/xyz/'], 'abcdef', $matches, PREG_RETURN_VALUE));
    }
    
    public function testReturnKeyFlag() {
        $this->assertEquals(1, preg_match_pattern_array(['/\d+/', '/abc/', '/xyz/'], 'abcdef', $matches, PREG_RETURN_KEY));
    }
    
    public function testOffsetCaptureFlag() {
        $this->assertEquals('/def/', preg_match_pattern_array(['/\d+/', '/def/', '/xyz/'], 'abcdef', $matches, PREG_OFFSET_CAPTURE));
        $this->assertEquals([['def', 3]], $matches);
    }
    
    public function testCombinedFlags() {
        $this->assertEquals(2, preg_match_pattern_array(['/\d+/' => 1, '/abc/' => 2, '/xyz/' => 3], 'abcdef', $matches, PREG_MATCH_KEY | PREG_RETURN_VALUE));
    }
    
}
