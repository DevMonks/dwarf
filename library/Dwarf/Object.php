<?php

namespace Dwarf;

class Object {
    
    public function getClass() {
        
        return get_class( $this );
    }
    
    public function __toString() {
        
        $class = $this->getClass();
        
        return "[$class Object]";
    }
    
    public static function box( $value ) {
        
        return $value instanceof static ? $value : new static( $value );
    }
}