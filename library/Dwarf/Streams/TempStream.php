<?php

namespace Dwarf\Streams;

class TempStream extends \Dwarf\Stream {
    
    public function __construct( $data = null, $size = '2M' ) {
        
        parent::__construct( 'php://temp/maxmemory:'.$size, self::MODE_READ_WRITE );
        
        if( $data ) {
            
            $this->write( $data );
            $this->seekStart();
        }
    }
}