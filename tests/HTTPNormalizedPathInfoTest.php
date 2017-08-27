<?php

use PHPUnit\Framework\TestCase;

class HttpNormalizedPathInfoTest extends TestCase {
    
    public function testAll() {
        $this->assertEquals('/', http_normalized_path_info('/my_app', '/my_app/index.php'));
        $this->assertEquals('/', http_normalized_path_info('/my_app/', '/my_app/index.php'));
        $this->assertEquals('/test', http_normalized_path_info('/my_app/test', '/my_app/index.php'));
        $this->assertEquals('/test', http_normalized_path_info('/my_app/test/', '/my_app/index.php'));
        $this->assertEquals('/test', http_normalized_path_info('/my_app/test?id=123', '/my_app/index.php'));
        $this->assertEquals('/', http_normalized_path_info('/my_app/index.php', '/my_app/index.php'));
        $this->assertEquals('/test', http_normalized_path_info('/my_app/index.php/test', '/my_app/index.php'));
        $this->assertEquals('/test', http_normalized_path_info('/my_app/index.php/test/', '/my_app/index.php'));
        $this->assertEquals('/test', http_normalized_path_info('/my_app/index.php/test?id=123', '/my_app/index.php'));
    }
    
}
