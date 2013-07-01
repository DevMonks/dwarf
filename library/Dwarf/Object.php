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
    
    /* Conversion methods */
    public function toString() {
        
        return (string)$this;
    }
}