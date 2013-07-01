<?php

namespace Dwarf\Streams;

class InputStream extends FileStream {
    
    public function __construct() {
        
        parent::__construct( 'php://input', static::MODE_READ );
    }
}