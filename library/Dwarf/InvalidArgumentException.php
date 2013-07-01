<?php

namespace Dwarf;

class InvalidArgumentException extends Exception {
    
    protected $argumentName;
    protected $requirement;
    
    public function __construct( $argName, $requirement = null, $code = 0 ) {
        
        $this->argName = $argName;
        $this->requirement = $requirement;
        
        $msg = "Invalid argument $argName specified.";
        
        if( $requirement )
            $msg .= " Value should be $requirement.";
        
        parent::__construct( $msg, $code );
    }
    
    public function getArgumentName() {
        
        return $this->argumentName;
    }
    
    public function getRequirement() {
        
        return $this->requirement;
    }
}