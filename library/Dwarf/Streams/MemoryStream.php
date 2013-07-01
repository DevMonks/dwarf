<?php

namespace Dwarf\Streams;

class MemoryStream extends FileStream {
    
    public function __construct( $data = null ) {
        
        parent::__construct( 'php://memory', static::MODE_READ_WRITE );
        
        if( $data ) {
            $this->write( $data );
            $this->seekStart();
        }
    }
}