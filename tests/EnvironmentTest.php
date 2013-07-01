<?php

class EnvironmentTest extends PHPUnit_Framework_TestCase {
    
    /**
     * @param type $env The environment to test
     * @dataProvider provider
     */
    public function testEnvironment( $env ) {
        
        $env = Dwarf\Environment::load( $env );
        
        $this->assertInstanceOf( 'Dwarf\\Instance', $env );
    }
    
    public function provider() {
        
        return array(
            'development',
            'production'
        );
    }
}