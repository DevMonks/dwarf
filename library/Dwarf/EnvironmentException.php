<?php

namespace Dwarf;

class EnvironmentException extends Exception {
    
    protected $environment;
    
    public function __construct( $message, $env = null, $code = 0 ) {
        
        $this->environment = $env;
        
        parent::__construct( $message, $code );
    }
    
    public function getEnvironment() {
        
        return $this->environment;
    }
}