<?php

use PHPUnit\Framework\TestCase;

class RequireWithLocalsTest extends TestCase {
    
    public function setUp() {
        global $myGlobal;
        $myGlobal = 123;
    }
    
    public function testNoVariables() {
        $this->expectOutputString('(myGlobal=not set)(var1=not set)(var2=not set)');
        require_with_locals(__DIR__ . '/data/require.php');
    }
    
    public function testManyVariables() {
        $this->expectOutputString('(myGlobal=not set)(var1=false)(var2={"name":"joe"})');
        require_with_locals(__DIR__ . '/data/require.php', [
            'var1' => false,
            'var2' => ['name' => 'joe']
        ]);
    }
    
}
