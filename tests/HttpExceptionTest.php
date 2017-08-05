<?php

use PHPUnit\Framework\TestCase;

class HttpExceptionTest extends TestCase {
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage At least one argument is expected
     */
    public function testNoArguments() {
        new HttpException();
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The status code must always be specified
     */
    public function testString() {
        new HttpException('custom message');
    }
    
    public function testInt() {
        $e = new HttpException(HttpException::HTTP_INTERNAL_SERVER_ERROR);
        $this->assertEquals('Internal Server Error', $e->getMessage());
        $this->assertEquals(500, $e->getCode());
    }
    
    public function testStringInt() {
        $e = new HttpException('Whoops!', HttpException::HTTP_INTERNAL_SERVER_ERROR);
        $this->assertEquals('Whoops!', $e->getMessage());
        $this->assertEquals(500, $e->getCode());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The status code must always come after the message
     */
    public function testIntString() {
        new HttpException(HttpException::HTTP_INTERNAL_SERVER_ERROR, 'Whoops!');
    }
    
    public function testIntException() {
        $previous = new InvalidArgumentException();
        
        $e = new HttpException(HttpException::HTTP_INTERNAL_SERVER_ERROR, $previous);
        $this->assertEquals('Internal Server Error', $e->getMessage());
        $this->assertEquals(500, $e->getCode());
        $this->assertSame($previous, $e->getPrevious());
    }
    
    public function testStringIntException() {
        $previous = new InvalidArgumentException();
        
        $e = new HttpException('Whoops!', HttpException::HTTP_INTERNAL_SERVER_ERROR, $previous);
        $this->assertEquals('Whoops!', $e->getMessage());
        $this->assertEquals(500, $e->getCode());
        $this->assertSame($previous, $e->getPrevious());
    }
    
}
