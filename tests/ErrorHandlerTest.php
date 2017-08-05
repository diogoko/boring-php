<?php

use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase {
    
    public function testThrows() {
        $old_level = error_reporting();
        try {
            error_reporting(E_ALL);
            error_to_exception_handler(E_WARNING, 'test message', 'myfile.php', 123);
            
            $this->fail('Should have raised \ErrorException');
        } catch (Exception $e) {
            $this->assertInstanceOf(\ErrorException::class, $e);
            $this->assertEquals('test message', $e->getMessage());
            $this->assertEquals('myfile.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());
        } finally {
            error_reporting($old_level);
        }
    }
    
    public function testDoesNotThrow() {
        $old_level = error_reporting();
        try {
            error_reporting(E_ERROR);
            $this->assertNull(error_to_exception_handler(E_WARNING, 'test message', 'myfile.php', 123));
        } finally {
            error_reporting($old_level);
        }
    }
    
}
