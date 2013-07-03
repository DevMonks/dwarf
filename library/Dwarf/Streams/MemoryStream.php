<?php

namespace Dwarf\Streams;

class MemoryStream extends \Dwarf\Stream {
    
    public function __construct( $data = null ) {
        
        parent::__construct( 'php://memory', self::MODE_READ_WRITE );
        
        if( $data ) {
            
            $this->write( $data );
            $this->seekStart();
        }
    }
}