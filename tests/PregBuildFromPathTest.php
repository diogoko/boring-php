<?php

use PHPUnit\Framework\TestCase;

class PregBuildFromPathTest extends TestCase {
    
    public function testNoVariables() {
        $this->assertEquals('#^/$#', preg_build_from_path('/'));
        $this->assertEquals('#^/documents$#', preg_build_from_path('/documents'));
    }
    
    public function testWithVariables() {
        $this->assertEquals('#^/documents/(?P<id>[^/]+)$#', preg_build_from_path('/documents/:id'));
        $this->assertEquals('#^/documents/(?P<id>[^/]+)/edit$#', preg_build_from_path('/documents/:id/edit'));
        $this->assertEquals('#^/documents/(?P<id>[^/]+)/(?P<format>[^/]+)$#', preg_build_from_path('/documents/:id/:format'));
    }
    
}
