<?php

use PHPUnit\Framework\TestCase;

class HttpNormalizedBasePathTest extends TestCase {
    
    public function testAll() {
        $this->assertEquals('/my_app', http_normalized_base_path('/my_app', '/my_app/index.php'));
        $this->assertEquals('/my_app', http_normalized_base_path('/my_app/', '/my_app/index.php'));
        $this->assertEquals('/my_app', http_normalized_base_path('/my_app/test', '/my_app/index.php'));
        $this->assertEquals('/my_app', http_normalized_base_path('/my_app/test/', '/my_app/index.php'));
        $this->assertEquals('/my_app', http_normalized_base_path('/my_app/test?id=123', '/my_app/index.php'));
        $this->assertEquals('/my_app/index.php', http_normalized_base_path('/my_app/index.php', '/my_app/index.php'));
        $this->assertEquals('/my_app/index.php', http_normalized_base_path('/my_app/index.php/test', '/my_app/index.php'));
        $this->assertEquals('/my_app/index.php', http_normalized_base_path('/my_app/index.php/test/', '/my_app/index.php'));
        $this->assertEquals('/my_app/index.php', http_normalized_base_path('/my_app/index.php/test?id=123', '/my_app/index.php'));
    }
    
}
