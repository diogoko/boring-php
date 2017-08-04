<?php

use PHPUnit\Framework\TestCase;

class EscapeTest extends TestCase {
    
    public function testCommonChars() {
        $this->assertEquals('&quot;Joe &amp; Son&#039;s&quot; &lt;joe@example.com&gt;', e('"Joe & Son\'s" <joe@example.com>'));
    }
    
}
