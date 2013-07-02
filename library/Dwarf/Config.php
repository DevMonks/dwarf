<?php

namespace Dwarf;

class Config extends Container {
    
    public static function load( $path, $parser = null ) {
        
        $path = Path::box( $path );
        
        $data = Parser::load( $path, $parser );
        
        return new static( $data );
    }
}