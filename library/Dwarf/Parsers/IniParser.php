<?php

namespace Dwarf\Parsers;

class IniParser extends \Dwarf\Parser {
    
    public function parseString( $string ) {
        
        $processSections = $this->getConfig( 'processSections', true );
        
        return parse_ini_string( $string, $processSections );
    }
}