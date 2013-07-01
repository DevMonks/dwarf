<?php

namespace Dwarf\Streams;

class TempStream extends FileStream {
    
    public function __construct( $data = null, $size = '2M' ) {
        
        parent::__construct( 'php://temp/maxmemory:'.$size, static::MODE_READ_WRITE );
        
        if( $data )
            $this->write( $data );
    }
}