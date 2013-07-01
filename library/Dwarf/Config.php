<?php

namespace Dwarf;

class Config implements \IteratorAggregate {
    
    protected $config;
    
    public function getIterator() {
        
        return new ArrayIterator( $this->config );
    }
}