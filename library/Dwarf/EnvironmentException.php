<?php

namespace Dwarf;

class EnvironmentException extends Exception {
    
    protected $env;
    
    public function __construct( $message, $env = null, $code = 0 ) {
        
        $this->env = $env;
        
        parent::__construct( $message, $code );
    }
}