<?php

namespace Dwarf\Net;

class Query extends \Dwarf\Container {
    
    public function __construct( $data = null ) {
        
        if( $data )
            $this->set( $data );
    }
    
    public function set( $data ) {
        
        if( is_string( $data ) ) {
            
            $vars = [];
            parse_str( $data, $vars );
            $this->merge( $vars );
        } else {
            
            $this->merge( $data );
        }
    }
    
    public function __toString() {
        
        return http_build_query( $this->getArray() );
    }
}