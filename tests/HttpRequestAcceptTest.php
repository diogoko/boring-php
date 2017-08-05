<?php

use PHPUnit\Framework\TestCase;

class HttpRequestAcceptTest extends TestCase {
    
    public function setUp() {
        unset($_SERVER);
    }
    
    public function tearDown() {
        unset($_SERVER);
    }
    
    public function testHTML() {
        $_SERVER['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $this->assertFalse(http_request_accept('application/json'));
    }
    
    public function testAJAX() {
        $_SERVER['HTTP_ACCEPT'] = 'application/json;q=0.9,*/*;q=0.8';
        $this->assertTrue(http_request_accept('application/json'));
    }
    
}
