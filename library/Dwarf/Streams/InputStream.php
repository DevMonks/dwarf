<?php

namespace Dwarf\Streams;

class InputStream extends Dwarf\Stream {
    
    public function __construct() {
        
        parent::__construct( 'php://input', self::MODE_READ );
    }
}