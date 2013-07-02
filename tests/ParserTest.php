<?php

require_once dirname( __DIR__ ).'/vendor/autoload.php';

class ParserTest extends PHPUnit_Framework_TestCase {
    
    public function testLoadRecognizesYaml() {
        
        $yaml = null;
        try {
            
            $yaml = Dwarf\Parser::load( dirname( __DIR__ ).'/app/config.yaml' );
        } catch( Exception $e ) {
            
            $this->assertTrue( false, $e->getMessage().', '.$e->getFile().':'.$e->getLine() );
        }
        
        $this->assertTrue( true );
    }
}