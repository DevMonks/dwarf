<?php

namespace Dwarf\Streams;

class OutputStream extends \Dwarf\Stream {
    
    public function __construct( $data = null ) {
        
        parent::__construct( 'php://output', self::MODE_WRITE );
        
        if( $data ) {
            
            $this->write( $data );
            $this->seekStart();
        }
    }
}