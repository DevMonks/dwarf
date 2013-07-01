<?php

namespace Dwarf\Streams;

class OutputStream extends FileStream {
    
    public function __construct() {
        
        parent::__construct( 'php://output', static::MODE_WRITE );
    }
}